<?php

namespace App\Http\Controllers;

use App\Models\Assist;
use App\Models\Pdv;
use App\Models\Worker;
use App\Models\Zonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Verificar que el usuario tenga roles asignados
            if (!$user || !method_exists($user, 'hasRole')) {
                return inertia('Errors/403');
            }
            
            $fechaActual = now()->setTimezone('America/Lima')->format('Y-m-d');
            $fechaAyer = now()->setTimezone('America/Lima')->subDay()->format('Y-m-d');

            if ($user->hasRole('administrador') || $user->hasRole('visualizador')) {
                $assists = Assist::where('current_date', $fechaActual)->count();
                $workers = Worker::count();
            } else {
                $pdvId = $user->pdv_id;
                
                // Validar que el usuario tenga pdv_id asignado
                if (!$pdvId) {
                    $assists = 0;
                    $workers = 0;
                } else {
                    $assists = Assist::whereHas('worker.pdv', function ($query) use ($pdvId) {
                        $query->where('id', $pdvId);
                    })->where('current_date', $fechaActual)->count();
                    $workers = Worker::whereHas('pdv', function ($query) use ($pdvId) {
                        $query->where('id', $pdvId);
                    })->count();
                }
            }

        $zonalsData = Zonal::with(['pdvs.workers.assists' => function ($query) use ($fechaActual) {
            $query->where('current_date', $fechaActual);
        }])->get()->map(function ($zonal) {
            if (!$zonal->pdvs) {
                return [
                    'zonal_id' => $zonal->id,
                    'zonal_name' => $zonal->name,
                    'assists_count' => 0,
                ];
            }
            
            $count = $zonal->pdvs->reduce(function ($carry, $pdv) {
                if (!$pdv->workers) {
                    return $carry;
                }
                return $carry + $pdv->workers->reduce(function ($carry, $worker) {
                    return $carry + ($worker->assists ? $worker->assists->count() : 0);
                }, 0);
            }, 0);

            return [
                'zonal_id' => $zonal->id,
                'zonal_name' => $zonal->name,
                'assists_count' => $count,
            ];
        });

        $zonalsDataAyer = Zonal::with(['pdvs.workers.assists' => function ($query) use ($fechaAyer) {
            $query->where('current_date', $fechaAyer);
        }])->get()->map(function ($zonal) {
            if (!$zonal->pdvs) {
                return [
                    'zonal_id' => $zonal->id,
                    'zonal_name' => $zonal->name,
                    'assists_count' => 0,
                ];
            }
            
            $count = $zonal->pdvs->reduce(function ($carry, $pdv) {
                if (!$pdv->workers) {
                    return $carry;
                }
                return $carry + $pdv->workers->reduce(function ($carry, $worker) {
                    return $carry + ($worker->assists ? $worker->assists->count() : 0);
                }, 0);
            }, 0);

            return [
                'zonal_id' => $zonal->id,
                'zonal_name' => $zonal->name,
                'assists_count' => $count,
            ];
        });

        $pdvs = Pdv::where('status', 1)->count();
        $zonals = Zonal::where('status', 1)->count();

        $absencesData = Zonal::with(['pdvs.workers.absences' => function ($query) use ($fechaActual) {
            $query->where('start_date', '<=', $fechaActual)
                ->where(function ($query) use ($fechaActual) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $fechaActual);
                });
        }])->get()->map(function ($zonal) {
            if (!$zonal->pdvs) {
                return [
                    'zonal_id' => $zonal->id,
                    'zonal_name' => $zonal->name,
                    'absences_count' => 0,
                ];
            }
            
            $count = $zonal->pdvs->reduce(function ($carry, $pdv) {
                if (!$pdv->workers) {
                    return $carry;
                }
                return $carry + $pdv->workers->reduce(function ($carry, $worker) {
                    return $carry + ($worker->absences ? $worker->absences->count() : 0);
                }, 0);
            }, 0);

            return [
                'zonal_id' => $zonal->id,
                'zonal_name' => $zonal->name,
                'absences_count' => $count,
            ];
        });

        $asistenciasPorEstado = Assist::selectRaw('status_entry, COUNT(*) as count')
            ->where('current_date', $fechaActual) // Filtrar por la fecha actual
            ->groupBy('status_entry')
            ->get()
            ->mapWithKeys(function ($asistencia) {
                return [$asistencia->status_entry => $asistencia->count];
            });

        // Obtener inasistencias agrupadas por estado
        $inasistenciasPorEstado = DB::table('absences')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function ($inasistencia) {
                return [
                    'status' => $inasistencia->status,
                    'count' => $inasistencia->count,
                ];
            });

        return inertia('Dashboard', compact('assists', 'workers', 'pdvs', 'zonals', 'zonalsData', 'zonalsDataAyer', 'absencesData', 'asistenciasPorEstado', 'inasistenciasPorEstado'));
        
        } catch (\Exception $e) {
            // Log del error para debug en producción
            \Log::error('Dashboard Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            
            // Retornar error 500 con información útil
            return inertia('Errors/500', [
                'message' => config('app.debug') ? $e->getMessage() : 'Error al cargar el dashboard',
            ]);
        }
    }
}
