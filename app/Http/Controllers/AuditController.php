<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuditController extends Controller
{
    
    public function index(Request $request)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'gerant']), 403);

        return $this->buildAudit($request, 'audit', ['Entres', 'Sorties']);
    }

    // 🔹 Admin uniquement : tout, y compris utilisateurs et connexions
    public function complet(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return $this->buildAudit($request, 'audit-complet', null); // null = pas de restriction de type
    }

    private function buildAudit(Request $request, string $view, ?array $modelTypes)
    {
        $periode = $request->input('periode', 'jour');
        $date    = $request->input('date', now()->toDateString());
        $ref     = Carbon::parse($date);

        switch ($periode) {
            case 'mois':
                $start = $ref->copy()->startOfMonth();
                $end   = $ref->copy()->endOfMonth();
                break;
            case 'annee':
                $start = $ref->copy()->startOfYear();
                $end   = $ref->copy()->endOfYear();
                break;
            default:
                $start = $ref->copy()->startOfDay();
                $end   = $ref->copy()->endOfDay();
        }

       $logs = AuditLog::whereBetween('created_at', [$start, $end])
           ->when($modelTypes, fn ($q) => $q->whereIn('model_type', $modelTypes))
            ->when($request->filled('plaque'), fn ($q) =>
            $q->where('plaque', 'like', '%' . $request->plaque . '%'))
            ->when($request->filled('plaque'), fn ($q) =>
                $q->where('plaque', 'like', '%' . $request->plaque . '%'))
            ->when($request->filled('user_name'), fn ($q) =>
                $q->where('user_name', 'like', '%' . $request->user_name . '%'))
            ->when($request->filled('action'), fn ($q) =>
                $q->where('action', $request->action))
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();

        return view($view, compact('logs', 'periode', 'date', 'start', 'end'));
    }

    public function history($uuid)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'gerant']), 403);

        $logs = AuditLog::where('model_uuid', $uuid)->orderBy('created_at')->get();
        abort_if($logs->isEmpty(), 404);

        $fields = collect();
        foreach ($logs as $log) {
            $fields = $fields
                ->merge(array_keys($log->old_values ?? []))
                ->merge(array_keys($log->new_values ?? []));
        }
        $fields = $fields->unique()->values();

        $events = $logs->map(function ($log) use ($fields) {
            $values  = [];
            $changed = [];
            foreach ($fields as $f) {
                if ($log->action === 'created') {
                    $values[$f] = $log->new_values[$f] ?? null;
                    $changed[]  = $f;
                } elseif ($log->action === 'updated' && array_key_exists($f, $log->new_values ?? [])) {
                    $values[$f] = $log->new_values[$f];
                    $changed[]  = $f;
                } elseif ($log->action === 'deleted') {
                    $values[$f] = $log->old_values[$f] ?? null;
                } else {
                    $values[$f] = null;
                }
            }
            return [
                'label' => match ($log->action) {
                    'created' => 'Création', 'updated' => 'Modification',
                    'deleted' => 'Suppression', default => $log->action,
                },
                'date'    => $log->created_at->format('d/m/Y H:i:s'),
                'user'    => $log->user_name,
                'values'  => $values,
                'changed' => $changed,
            ];
        });

        $premier = $logs->first();

        return view('audit-history', [
            'fields' => $fields, 'events' => $events,
            'plaque' => $premier->plaque, 'model_type' => $premier->model_type, 'uuid' => $uuid,
        ]);
    }
}