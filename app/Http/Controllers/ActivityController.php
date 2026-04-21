<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityController extends Controller
{
    public function showEntre($id)
    {
        $entree = Entres::findOrFail($id);
        $hasSortie = Sorties::where('plaque', $entree->plaque)
                            ->where('created_at', '>', $entree->created_at)
                            ->exists();
        return view('entres.show', compact('entree', 'hasSortie'));
    }

    public function showSortie($id)
    {
        $sortie = Sorties::findOrFail($id);
        return view('sorties.show', compact('sortie'));
    }

    public function activites(Request $request)
    {
        $user = auth()->user();
        $plaque = $request->input('plaque');
        $type   = $request->input('type');
        $periode = $this->resolvePeriode($request->input('periode'));

        $activites = collect();

        if ($user->role === 'agent') {
            // Entrées de l'agent
            $entres = $this->applyFilters(Entres::where('user_id', $user->id), $plaque, $type, $periode)->get();
            foreach ($entres as $entre) {
                $activites->push([
                    'id'        => $entre->id,
                    'source'    => 'entree',
                    'plaque'    => $entre->plaque,
                    'evenement' => 'Entrée',
                    'name'      => $entre->name ?? '-',
                    'type'      => ucfirst($entre->type),
                    'date'      => $entre->created_at->format('d/m/Y H:i'),
                    'entre'     => $entre->created_at->format('H:i'),
                    'sortie'    => '-',
                    'duree'     => '-',
                    'statut'    => 'En cours',
                    'raw_date'  => $entre->created_at, // Pour le tri
                ]);
            }

            // Sorties de l'agent
            $sorties = $this->applyFilters(Sorties::where('user_id', $user->id), $plaque, $type, $periode)->get();
            foreach ($sorties as $sortie) {
                $activites->push([
                    'id'        => $sortie->id,
                    'source'    => 'sortie',
                    'plaque'    => $sortie->plaque,
                    'evenement' => 'Sortie',
                    'name'      => $sortie->owner_name ?? '-',
                    'type'      => ucfirst($sortie->type),
                    'date'      => $sortie->created_at->format('d/m/Y H:i'),
                    'entre'     => '-',
                    'sortie'    => $sortie->created_at->format('H:i'),
                    'duree'     => '-',
                    'statut'    => 'Terminé',
                    'raw_date'  => $sortie->created_at,
                ]);
            }
        } 
        else {
            // ADMIN / GÉRANT : On couple Entrée et Sortie
            $entres = $this->applyFilters(Entres::query(), $plaque, $type, $periode)->get();
            $sorties = Sorties::all()->groupBy('plaque');

            foreach ($entres as $entre) {
                $sortie = optional($sorties[$entre->plaque] ?? collect())
                    ->firstWhere('created_at', '>', $entre->created_at);

                $activites->push([
                    // Si sorti, on pointe vers l'ID de la sortie, sinon vers l'entrée
                    'id'        => $sortie ? $sortie->id : $entre->id, 
                    'source'    => $sortie ? 'sortie' : 'entree', 
                    'plaque'    => $entre->plaque,
                    'evenement' => $sortie ? 'Sortie' : 'Entrée',
                    'name'      => $entre->name ?? '-',
                    'type'      => ucfirst($entre->type),
                    'date'      => $entre->created_at->format('d/m/Y'),
                    'entre'     => $entre->created_at->format('H:i'),
                    'sortie'    => $sortie ? $sortie->created_at->format('H:i') : '-',
                    'duree'     => $sortie ? $entre->created_at->diff($sortie->created_at)->format('%hh %imin') : '-',
                    'statut'    => $sortie ? 'Complété' : 'En attente',
                    'raw_date'  => $entre->created_at,
                ]);
            }
        }

        // Tri par date décroissante (les plus récents en haut)
        $activites = $activites->sortByDesc('raw_date')->values();

        $page = $request->input('page', 1);
        $perPage = 15;
        $activites = new LengthAwarePaginator(
            $activites->forPage($page, $perPage),
            $activites->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('recent', compact('activites', 'plaque', 'type'));
    }

    private function applyFilters($query, $plaque, $type, $periode)
    {
        if ($plaque) $query->where('plaque', 'like', "%$plaque%");
        if ($type) $query->where('type', $type);
        if ($periode) $query->whereBetween('created_at', [$periode['from'], $periode['to']]);
        return $query;
    }

    private function resolvePeriode($periode)
    {
        if (!$periode) return null;
        return match ($periode) {
            'jour'    => ['from' => now()->startOfDay(),   'to' => now()->endOfDay()],
            'semaine' => ['from' => now()->startOfWeek(),  'to' => now()->endOfWeek()],
            'mois'    => ['from' => now()->startOfMonth(), 'to' => now()->endOfMonth()],
            'année'   => ['from' => now()->startOfYear(),  'to' => now()->endOfYear()],
            default   => null,
        };
    }
}