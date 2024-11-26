<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistRequest;
use App\Models\Assist;
use App\Models\Worker;
use Carbon\Carbon;
use Exception;
use Inertia\Inertia;
use Inertia\Response;

class AssistController extends Controller
{

    public function index(): Response
    {
        $user = auth()->user();
        $fechaActual = now()->setTimezone('America/Lima')->format('Y-m-d');

        if ($user->hasRole('administrador') || $user->hasRole('visualizador')) {
            $assists = Assist::with(['worker.pdv.zonal'])
                ->where('current_date', $fechaActual)
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        } else {
            $pdvId = $user->pdv_id;
            $assists = Assist::with(['worker.pdv.zonal'])
                ->whereHas('worker.pdv', function ($query) use ($pdvId) {
                    $query->where('id', $pdvId);
                })
                ->where('current_date', $fechaActual)
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        }

        if (!$user->hasRole('visualizador')) {
            return Inertia::render('Assist/Assist/Index', compact('assists'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(AssistRequest $request)
    {
        try {
            $assist = new Assist();
            $dayMap = [
                'Monday' => 'Lunes',
                'Tuesday' => 'Martes',
                'Wednesday' => 'Miercoles',
                'Thursday' => 'Jueves',
                'Friday' => 'Viernes',
                'Saturday' => 'Sabado',
                'Sunday' => 'Domingo',
            ];

            $dayEn = now()->format('l');
            $dayEs = $dayMap[$dayEn] ?? 'Día no reconocido';
            $horaActual = now()->setTimezone('America/Lima')->format('H:i:s');
            $fechaActual = now()->setTimezone('America/Lima')->format('Y-m-d');

            $worker = Worker::with('pdv.horary.detailhoraries')->findOrFail($request->id);
            $horarios = $worker->pdv->horary->detailhoraries;
            $existe = $horarios->firstWhere('week', $dayEs);

            if (!$existe) {
                return redirect()->route('assist.index')->with('toast', ['Horario no encontrado para el día actual', 'warning']);
            }

            $status = $this->determineStatus($request->tipoRegistro, $horaActual, $existe);

            if ($status === null) {
                return redirect()->route('assist.index')->with('toast', ['Necesita el tipo de registro', 'warning']);
            }

            $assistData = [
                'worker_id' => $request->id,
                'current_date' => $fechaActual,
                'hi' => $request->tipoRegistro === 'Ingreso' ? $horaActual : null,
                'rs' => $request->tipoRegistro === 'Refrigerio/S' ? $horaActual : null,
                'ri' => $request->tipoRegistro === 'Refrigerio/I' ? $horaActual : null,
                'hs' => $request->tipoRegistro === 'Salida' ? $horaActual : null,
                'status_entry' => $request->tipoRegistro === 'Ingreso' ? $status : null,
                'status_foodout' => $request->tipoRegistro === 'Refrigerio/S' ? $status : null,
                'status_foodentry' => $request->tipoRegistro === 'Refrigerio/I' ? $status : null,
                'status_out' => $request->tipoRegistro === 'Salida' ? $status : null,
            ];

            $existingAssist = Assist::where('worker_id', $request->id)
                ->where('current_date', $fechaActual)
                ->first();

            if ($existingAssist) {
                $existingAssist->update(array_filter($assistData, fn($value) => !is_null($value)));
            } else {
                $assist->fill($assistData);
                $assist->save();
            }

            return redirect()->route('assist.index')->with('toast', ['Registro exitosamente!', 'success']);
        } catch (Exception $e) {
            return redirect()->route('assist.index')->with('toast', ['Ocurrió un error al registrar la asistencia.', 'danger']);
        }
    }

    private function determineStatus($tipoRegistro, $horaActual, $existe)
    {
        $status = null;
        switch ($tipoRegistro) {
            case 'Ingreso':
                $tolerancia = Carbon::parse($existe->hi)->addMinutes(5)->format('H:i:s');
                $status = $horaActual > $tolerancia ? 0 : ($horaActual > $existe->hi ? 2 : 1);
                break;
            case 'Refrigerio/S':
                $status = $horaActual > $existe->rs ? 1 : 0;
                break;
            case 'Refrigerio/I':
                $tolerancia = Carbon::parse($existe->ri)->addMinutes(5)->format('H:i:s');
                $status = $horaActual > $tolerancia ? 0 : ($horaActual > $existe->ri ? 2 : 1);
                break;
            case 'Salida':
                $status = $horaActual > $existe->hs ? 1 : 0;
                break;
        }
        return $status;
    }

    public function getWorker($dni)
    {
        $user = auth()->user();
        $worker = Worker::with(['pdv'])->where('num_document', $dni)->first();

        if (empty($dni) || is_null($worker)) {
            return redirect()->route('assist.index')->with('toast', ['Trabajador no encontrado', 'warning']);
        }

        $fechaActual = now()->setTimezone('America/Lima')->format('Y-m-d');

        if ($user->hasRole('administrador') || $user->hasRole('visualizador')) {
            $assists = Assist::with(['worker.pdv.zonal'])
                ->where('worker_id', $worker->id)
                ->where('current_date', $fechaActual)
                ->orderBy('current_date', 'desc')
                ->paginate(10);
        } else {
            $pdvId = $user->pdv_id;
            $assists = Assist::with(['worker.pdv.zonal'])
                ->where('worker_id', $worker->id)
                ->whereHas('worker.pdv', function ($query) use ($pdvId) {
                    $query->where('id', $pdvId);
                })
                ->where('current_date', $fechaActual)
                ->orderBy('current_date', 'desc')
                ->paginate(10);
        }

        return Inertia::render('Assist/Assist/Index', [
            'worker' => $worker,
            'dni' => $dni,
            'assists' => $assists,
        ])->with('toast', ['Trabajador encontrado', 'success']);
    }
}
