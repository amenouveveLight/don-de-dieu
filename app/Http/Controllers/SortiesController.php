<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class SortiesController extends Controller
{
    /* ================= TYPES & TARIFS ================= */

    private function getTypes(): array
    {
        return[
            'motorcycle' => ['label' => 'Moto',     'color' => '#3b82f6'],
            'car'        => ['label' => 'Voiture',  'color' => '#22c55e'],
            'tricycle'   =>['label' => 'Tricycle', 'color' => '#ec4899'],
            'nyonyovi'   =>['label' => 'Nyonyovi', 'color' => '#0ea5e9'],
            'minibus'    =>['label' => 'Minibus',  'color' => '#eab308'],
            'bus'        =>['label' => 'Bus',      'color' => '#f97316'],
            'truck'      =>['label' => 'Camion',   'color' => '#8b5cf6'],
        ];
    }

    private function getTarifs(): array
    {
        return Tarif::all()
            ->keyBy('type')
            ->map(fn ($t) =>['label' => $t->label, 'tarif' => $t->tarif])
            ->toArray();
    }

    /* ================= STATISTIQUES ================= */

    private function genererStatistiques($periode, $date = null)
    {
        $user = auth()->user();
        $now = $date ? Carbon::parse($date) : now();

        if ($periode === 'semaine' && is_string($date) && preg_match('/^\d{4}-W\d{2}$/', $date)) {
            [$year, $weekNumber] = explode('-W', $date);
            $now = Carbon::now()->setISODate((int)$year, (int)$weekNumber)->startOfWeek();
        }

        switch ($periode) {
            case 'semaine':
                $start = $now->copy()->startOfWeek();
                $end   = $now->copy()->endOfWeek();
                break;
            case 'mois':
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                break;
            case 'année':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                break;
            default: // jour
                $start = $now->copy()->startOfDay();
                $end   = $now->copy()->endOfDay();
        }

        // Base queries (Optimisation: pas de boucle pour les requêtes)
        $entresQuery  = Entres::whereBetween('created_at', [$start, $end]);
        $sortiesQuery = Sorties::whereBetween('created_at', [$start, $end]);

        if ($user->role === 'agent') {
            $entresQuery->where('user_id', $user->id);
            $sortiesQuery->where('user_id', $user->id);
        }

       $totalEntrees = (clone $entresQuery)->count();

        // ⚡ OPTIMISATION : On groupe par type en UNE SEULE requête
        $entreesGrouped = (clone $entresQuery)
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $sortiesGrouped = (clone $sortiesQuery)
            ->select('type', DB::raw('COUNT(*) as total'), DB::raw('SUM(montant) as revenu'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $types = $this->getTypes();
        $circumference = 2 * pi() * 80;
        $offset = 0;

        $tableData = [];
        $pieSegments = [];
        $barSegments =[];

        $totalSorties = 0;
        $revenusTotaux = 0;
        $revenuMax = 0;

        foreach ($types as $type => $info) {
            // Lecture depuis les résultats groupés (Ultra-rapide)
            $entrees = $entreesGrouped[$type] ?? 0;
            $sorties = $sortiesGrouped[$type]->total ?? 0;
            $revenu  = $sortiesGrouped[$type]->revenu ?? 0;

            $tarifMoyen = $sorties > 0 ? round($revenu / $sorties, 2) : 0;

            $tableData[] = [
                'label'   => $info['label'],
                'entrees' => $entrees,
                'sorties' => $sorties,
                'tarif'   => $tarifMoyen,
                'revenu'  => $revenu,
                'color'   => $info['color'],
            ];

            $percent = $totalEntrees > 0 ? ($entrees / $totalEntrees) : 0;
            $length = $percent * $circumference;

            $pieSegments[] = [
                'label'      => $info['label'],
                'color'      => $info['color'],
                'dasharray'  => round($length, 2) . ' ' . round($circumference - $length, 2),
                'dashoffset' => -round($offset, 2),
                'percent'    => round($percent * 100, 1),
            ];

            $barSegments[] = [
                'label'  => $info['label'],
                'revenu' => $revenu,
                'color'  => $info['color'],
            ];

            $offset += $length;
            $totalSorties += $sorties;
            $revenusTotaux += $revenu;
            $revenuMax = max($revenuMax, $revenu);
        }

        return[
            'tableData'       => $tableData,
            'segments'        => $pieSegments,
            'revenuSegments'  => $barSegments,
            'revenuMax'       => $revenuMax,
            'totalEntrees'    => $totalEntrees,
            'totalSorties'    => $totalSorties,
            'revenusTotaux'   => $revenusTotaux,
            'tarifMoyenTotal' => $totalSorties > 0 ? round($revenusTotaux / $totalSorties, 2) : 0,
        ];
    }

    /* ================= DASHBOARD ================= */

    public function statistiques(Request $request)
    {
        $user = auth()->user();
        $date = $request->input('date', now()->format('Y-m-d'));

        // Stats par période (Beaucoup plus rapide avec l'optimisation)
        $daily   = $this->genererStatistiques('jour', $date);
        $weekly  = $this->genererStatistiques('semaine', $date);
        $monthly = $this->genererStatistiques('mois', $date);
        $yearly  = $this->genererStatistiques('année', $date);

        // Base queries
        $entresQuery  = Entres::query();
        $sortiesQuery = Sorties::query();

        if ($user->role === 'agent') {
            $entresQuery->where('user_id', $user->id);
            $sortiesQuery->where('user_id', $user->id);
        }

        // Présents actuels
        $oneHourAgo = now()->subHour();

        $currentEntrants = (clone $entresQuery)->count();
        $currentSorties = (clone $sortiesQuery)->count();
        $currentPresent = $currentEntrants - $currentSorties;

        $entrantsBefore = (clone $entresQuery)->where('created_at', '<=', $oneHourAgo)->count();
        $sortiesBefore = (clone $sortiesQuery)->where('created_at', '<=', $oneHourAgo)->count();
        $presentOneHourAgo = $entrantsBefore - $sortiesBefore;

        $diff = $currentPresent - $presentOneHourAgo;
        $diffFormatted = $diff > 0 ? "+{$diff}" : (string) $diff; // (Corrigé : si >0, c'est un +, sinon c'est géré par le signe -)

        // Capacité et occupation (Considération d'utiliser .env pour la capacité)
        $capaciteTotale = env('PARKING_CAPACITY', 500); 
        $placesOccupées = max(0, $currentPresent); // Évite un chiffre négatif en cas d'erreur de base de données
        $tauxOccupation = $capaciteTotale > 0 ? round(($placesOccupées / $capaciteTotale) * 100, 1) : 0;

        // Sorties par type + montant (agent ou global)
        $sortiesParType = (clone $sortiesQuery)
            ->select('type', DB::raw('COUNT(*) as total'), DB::raw('SUM(montant) as montant_total'))
            ->groupBy('type')
            ->get();

        // Revenu du jour
        $revenuJour = (clone $sortiesQuery)
            ->whereDate('created_at', today())
            ->sum('montant');

        // Top journée
        $topDailyEntry = (clone $entresQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('total')
            ->first();

        return view('dashboard',[
            // Daily
            'dailyTableData'       => $daily['tableData'],
            'dailyPieSegments'     => $daily['segments'],
            'dailyRevenusSegments' => $daily['revenuSegments'],
            'dailyRevenuMax'       => $daily['revenuMax'],
            'dailyTotalEntrees'    => $daily['totalEntrees'],
            'dailyTotalSorties'    => $daily['totalSorties'],
            'dailyRevenusTotaux'   => $daily['revenusTotaux'],
            'dailyTarifMoyenTotal' => $daily['tarifMoyenTotal'],

            // Weekly
            'weeklyTableData'       => $weekly['tableData'],
            'weeklyPieSegments'     => $weekly['segments'],
            'weeklyRevenusSegments' => $weekly['revenuSegments'],
            'weeklyRevenuMax'       => $weekly['revenuMax'],
            'weeklyTotalEntrees'    => $weekly['totalEntrees'],
            'weeklyTotalSorties'    => $weekly['totalSorties'],
            'weeklyRevenusTotaux'   => $weekly['revenusTotaux'],
            'weeklyTarifMoyenTotal' => $weekly['tarifMoyenTotal'],

            // Monthly
            'monthlyTableData'       => $monthly['tableData'],
            'monthlyPieSegments'     => $monthly['segments'],
            'monthlyRevenusSegments' => $monthly['revenuSegments'],
            'monthlyRevenuMax'       => $monthly['revenuMax'],
            'monthlyTotalEntrees'    => $monthly['totalEntrees'],
            'monthlyTotalSorties'    => $monthly['totalSorties'],
            'monthlyRevenusTotaux'   => $monthly['revenusTotaux'],
            'monthlyTarifMoyenTotal' => $monthly['tarifMoyenTotal'],

            // Yearly
            'yearlyTableData'       => $yearly['tableData'],
            'yearlyPieSegments'     => $yearly['segments'],
            'yearlyRevenusSegments' => $yearly['revenuSegments'],
            'yearlyRevenuMax'       => $yearly['revenuMax'],
            'yearlyTotalEntrees'    => $yearly['totalEntrees'],
            'yearlyTotalSorties'    => $yearly['totalSorties'],
            'yearlyRevenusTotaux'   => $yearly['revenusTotaux'],
            'yearlyTarifMoyenTotal' => $yearly['tarifMoyenTotal'],

            // Dashboard global
            'topDailyEntry'      => $topDailyEntry,
            'places_disponibles' => max(0, $capaciteTotale - $placesOccupées),
            'places_occupees'    => $placesOccupées,
            'evolution_1h'       => $diffFormatted,
            'evolution_occupees_1h' => $diff,
            'taux_occupation'    => min(100, $tauxOccupation), // Pour ne pas dépasser 100% visuellement
            'totalEngins'        => $currentEntrants,
            'revenuJour'         => $revenuJour,
            'sortiesParType'     => $sortiesParType,
        ]);
    }

    public function create(Request $request)
    {
        $types = $this->getTypes();
        $tarifs = $this->getTarifs();

        $plaque = $request->input('plaque');
        $dernierType = null;
        $montant = null;
        $joursPasses = 1;
        $typesDisponibles =[];

        if ($plaque) {
            $entrees = Entres::where('plaque', $plaque)->orderByDesc('created_at')->get();

            if ($entrees->isNotEmpty()) {
                $typesPlaque = $entrees->pluck('type')->unique();

                foreach ($typesPlaque as $type) {
                    $derniereEntree = $entrees->where('type', $type)->first();
                    $derniereSortie = Sorties::where('plaque', $plaque)
                        ->where('type', $type)
                        ->latest()
                        ->first();

                    if (!$derniereSortie || $derniereEntree->created_at > $derniereSortie->created_at) {
                        $typesDisponibles[] = $type;
                    }
                }

                if (!empty($typesDisponibles)) {
                    $dernierType = $typesDisponibles[0];
                    $dernierEntree = $entrees->where('type', $dernierType)->first();
                    // Simplification avec Carbon :
                    $joursPasses = $dernierEntree->created_at->diffInDays(now()) + 1;
                    $montant = ($tarifs[$dernierType]['tarif'] ?? 0) * $joursPasses;
                } else {
                    return back()->withErrors(['plaque' => 'Toutes les sorties ont déjà été enregistrées pour cette plaque.']);
                }
            }
        }

        return view('sorties', compact(
            'types', 'dernierType', 'montant', 'plaque', 'joursPasses', 'typesDisponibles'
        ));
    }

    public function store(Request $request)
    {
        $tarifs = $this->getTarifs();
        $types = $this->getTypes();

        $validated = $request->validate([
            'plaque'      => 'required|string|exists:entres,plaque',
            'type'        => 'required|string',
            'paiement'    => 'required|in:cash,card,app',
            'paiement_ok' => ['nullable', 'accepted'],
        ]);

        $entreesPlaque = Entres::where('plaque', $validated['plaque'])
            ->pluck('type')
            ->unique()
            ->toArray();

        if (!in_array($validated['type'], array_keys($types)) || !in_array($validated['type'], $entreesPlaque)) {
            return back()->withErrors(['type' => 'Le type sélectionné n’est pas valide pour cette plaque.']);
        }

        $entree = Entres::where('plaque', $validated['plaque'])
            ->where('type', $validated['type'])
            ->latest()
            ->first();

        if (!$entree) {
            return back()->withErrors(['plaque' => 'Aucune entrée trouvée pour cette plaque et ce type.']);
        }

        $derniereSortie = Sorties::where('plaque', $validated['plaque'])
            ->where('type', $validated['type'])
            ->latest()
            ->first();

        if ($derniereSortie && $derniereSortie->created_at >= $entree->created_at) {
            return back()->withErrors(['type' => 'La sortie pour ce type a déjà été enregistrée pour cette entrée.']);
        }

        // Simplification Carbon
        $joursPasses = $entree->created_at->diffInDays(now()) + 1;
        $tarifJournalier = $tarifs[$validated['type']]['tarif'] ?? 0;
        $montantTotal = $joursPasses * $tarifJournalier;

        $sortie = Sorties::create([
            'user_id'     => auth()->id(),
            'owner_name'  => $entree->name,
            'owner_phone' => $entree->phone,
            'plaque'      => $validated['plaque'],
            'type'        => $validated['type'],
            'montant'     => $montantTotal,
            'paiement'    => $validated['paiement'],
            'paiement_ok' => $request->has('paiement_ok'),
        ]);
                return redirect()->route('sorties.create')->with([
        'success' => 'Sortie enregistrée avec succès !',
        'ticket_url' => route('sorties.ticket.html', $sortie->id)
    ]);// Assurez-vous que cette route existe
        
    }

    

       public function show($id)
      {
                // On charge la sortie ET l'agent qui a fait la sortie ('user')
                 $sortie = Sorties::with('user')->findOrFail($id);

         // On cherche l'entrée correspondante (et l'agent qui a fait l'entrée)
                     $entree = Entrees::with('user')
                     ->where('plaque', $sortie->plaque)
                     ->where('created_at', '<=', $sortie->created_at)
                     ->orderBy('created_at', 'desc')
                     ->first();

                      $date_entree = $entree ? $entree->created_at : null;
                       $agent_entree = $entree ? $entree->user : null; // On récupère l'agent d'entrée

                      return view('sorties.show', compact('sortie', 'entree', 'agent_entree'));
}

    // 🔹 Formulaire édition
    public function edit($id)
    {
        $sortie = Sorties::findOrFail($id);
        return view('sorties.edit', compact('sortie'));
    }

    // 🔹 Mise à jour sortie
    public function update(Request $request, $id)
    {
        $sortie = Sorties::findOrFail($id);

        $validated = $request->validate([
            'paiement'    => 'required|in:cash,card,app',
            'paiement_ok' => 'nullable|accepted',
        ]);

        $sortie->update([
            'paiement'    => $validated['paiement'],
            'paiement_ok' => $request->has('paiement_ok'),
        ]);

        return redirect()
            ->route('sorties.show', $sortie->id)
            ->with('success', 'Sortie mise à jour avec succès.');
    }

    // 🔹 Suppression sortie
    public function destroy($id)
    {
        $sortie = Sorties::findOrFail($id);
        $sortie->delete();

        return redirect()->route('sorties.index')->with('success', 'Sortie supprimée avec succès.');      
    }

    public function exportJour(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $daily = $this->genererStatistiques('jour', $date);

        $pdf = Pdf::loadView('exports.jour',[
            'date'                 => $date,
            'dailyTableData'       => $daily['tableData'],
            'dailyPieSegments'     => $daily['segments'],
            'dailyRevenusSegments' => $daily['revenuSegments'],
            'dailyRevenuMax'       => $daily['revenuMax'],
            'dailyTotalEntrees'    => $daily['totalEntrees'],
            'dailyTotalSorties'    => $daily['totalSorties'],
            'dailyRevenusTotaux'   => $daily['revenusTotaux'],
            'dailyTarifMoyenTotal' => $daily['tarifMoyenTotal'],
        ]);

        return $pdf->download("statistiques_jour_{$date}.pdf");
    }

    public function exportSemaine(Request $request)
    {
        $weekInput = $request->input('week', now()->format('o-\WW')); 
        [$year, $weekNumber] = explode('-W', $weekInput);

        $week = Carbon::now()->setISODate((int)$year, (int)$weekNumber)->startOfWeek();
        $weekly = $this->genererStatistiques('semaine', $week);

        $pdf = Pdf::loadView('exports.semaine',[
            'week'                   => $week,
            'weeklyTableData'        => $weekly['tableData'],
            'weeklyPieSegments'      => $weekly['segments'],
            'weeklyRevenusSegments'  => $weekly['revenuSegments'],
            'weeklyRevenuMax'        => $weekly['revenuMax'],
            'weeklyTotalEntrees'     => $weekly['totalEntrees'],
            'weeklyTotalSorties'     => $weekly['totalSorties'],
            'weeklyRevenusTotaux'    => $weekly['revenusTotaux'],
            'weeklyTarifMoyenTotal'  => $weekly['tarifMoyenTotal'],
        ]);

        $filename = 'statistiques_semaine_' . $week->format('o-\WW') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportMois(Request $request)
    {
        $month = $request->input('month', now()->toDateString());
        $monthly = $this->genererStatistiques('mois', $month);

        $pdf = Pdf::loadView('exports.mois', [
            'month'                  => $month,
            'monthlyTableData'       => $monthly['tableData'],
            'monthlyPieSegments'     => $monthly['segments'],
            'monthlyRevenusSegments' => $monthly['revenuSegments'],
            'monthlyRevenuMax'       => $monthly['revenuMax'],
            'monthlyTotalEntrees'    => $monthly['totalEntrees'],
            'monthlyTotalSorties'    => $monthly['totalSorties'],
            'monthlyRevenusTotaux'   => $monthly['revenusTotaux'],
            'monthlyTarifMoyenTotal' => $monthly['tarifMoyenTotal'],
        ]);

        return $pdf->download("statistiques_mois_{$month}.pdf");
    }

    public function exportAnnee(Request $request)
    {
        $year = $request->input('year', now()->toDateString());
        $yearly = $this->genererStatistiques('année', $year);

        $pdf = Pdf::loadView('exports.année',[
            'year'                  => $year,
            'yearlyTableData'       => $yearly['tableData'],
            'yearlyPieSegments'     => $yearly['segments'],
            'yearlyRevenusSegments' => $yearly['revenuSegments'],
            'yearlyRevenuMax'       => $yearly['revenuMax'],
            'yearlyTotalEntrees'    => $yearly['totalEntrees'],
            'yearlyTotalSorties'    => $yearly['totalSorties'],
            'yearlyRevenusTotaux'   => $yearly['revenusTotaux'],
            'yearlyTarifMoyenTotal' => $yearly['tarifMoyenTotal'],
        ]);

        return $pdf->download("statistiques_année_{$year}.pdf");
    }

    public function downloadTicket($id)
    {
        $sortie = Sorties::findOrFail($id);
        $pdf = Pdf::loadView('ticket-sortie', compact('sortie'));
        return $pdf->stream('ticket.pdf');
    }

  
        
public function ticketHtml($id)
    {
        $sortie = Sorties::findOrFail($id);
        
        // On récupère l'entrée correspondante pour avoir la date d'arrivée
        $entree = Entres::where('plaque', $sortie->plaque)
            ->where('created_at', '<=', $sortie->created_at)
            ->latest()
            ->first();

        // Calcul du nombre de jours et gestion de la date d'entrée
        if ($entree) {
            $joursPasses = $entree->created_at->diffInDays(now()) + 1;
            $dateEntreeStr = $entree->created_at->format('d/m/Y H:i:s');
        } else {
            $joursPasses = 1;
            $dateEntreeStr = 'Non spécifiée';
        }

        $montantTotal = $sortie->montant;

        // 1. Préparer les données à mettre dans le QR Code
        $qrData = "TICKET SORTIE/REÇU N°: " . $sortie->id . "\n";
        $qrData .= "PLAQUE: " . $sortie->plaque . "\n";
        $qrData .= "TYPE: " . strtoupper($sortie->type) . "\n";
        $qrData .= "CLIENT: " . ($sortie->owner_name ?? 'Inconnu') . "\n";
        $qrData .= "TEL: " . ($sortie->owner_phone ?? 'Inconnu') . "\n";
        $qrData .= "ENTRÉE: " . $dateEntreeStr . "\n";
        $qrData .= "SORTIE: " . $sortie->created_at->format('d/m/Y H:i:s') . "\n";
        $qrData .= "DURÉE: " . $joursPasses . " Jour(s)\n";
        $qrData .= "MONTANT: " . $montantTotal . " FCFA\n";
        $qrData .= "PAIEMENT: " . strtoupper($sortie->paiement);

        // 2. Générer le QR code au format SVG
        // (Assurez-vous que "use SimpleSoftwareIO\QrCode\Facades\QrCode;" est bien importé en haut du fichier, mais ça doit l'être vu que ça marche pour les entrées)
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($qrData);

        // 3. Retourner la vue
        return view('ticket-sortie', compact('sortie', 'entree', 'joursPasses', 'montantTotal', 'qrCode'));
    }
    

    public function statsAgents(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'gerant'])) {
            abort(403, 'Accès refusé');
        }

        $periode = $request->input('periode', 'jour'); 
        $date    = $request->input('date', now()->toDateString());
        $now     = Carbon::parse($date);

        switch ($periode) {
            case 'semaine':
                $start = $now->copy()->startOfWeek();
                $end   = $now->copy()->endOfWeek();
                break;
            case 'mois':
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                break;
            case 'année':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                break;
            default: // jour
                $start = $now->copy()->startOfDay();
                $end   = $now->copy()->endOfDay();
        }

        $entreesParAgent = Entres::query()
            ->select('user_id', DB::raw('COUNT(*) as total_entrees'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('user_id')
            ->pluck('total_entrees', 'user_id');

        $sortiesParAgent = Sorties::query()
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_sorties'),
                DB::raw('SUM(montant) as montant_total')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $agentsData =[];
        $agents = User::where('role', 'agent')->get();

        foreach ($agents as $agent) {
            $agentsData[] =[
                'agent'         => trim($agent->firstname . ' ' . $agent->lastname),
                'total_entrees' => (int) ($entreesParAgent[$agent->id] ?? 0),
                'total_sorties' => (int) ($sortiesParAgent[$agent->id]->total_sorties ?? 0),
                'montant_total' => (float) ($sortiesParAgent[$agent->id]->montant_total ?? 0),
            ];
        }

        return view('statsagent', compact('agentsData','periode','date'));
    }
}