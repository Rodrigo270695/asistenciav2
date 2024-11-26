<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenceRequest;
use App\Models\Absence;
use App\Models\Worker;
use App\Models\Reason;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;

class AbsenceController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        if ($user->hasRole('administrador') || $user->hasRole('visualizador')) {
            $absences = Absence::with(['worker.pdv.zonal', 'reason'])->orderBy('created_at', 'desc')->paginate(10);
            $workers = Worker::whereNull('exit_date')->orWhere('exit_date', '')->orderBy('name', 'asc')->get();
        } elseif ($user->hasRole('supervisor')) {
            $pdvId = $user->pdv_id;
            $absences = Absence::with(['worker.pdv.zonal', 'reason'])
                ->whereHas('worker.pdv', function ($query) use ($pdvId) {
                    $query->where('id', $pdvId);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $workers = Worker::where('pdv_id', $pdvId)
                ->whereNull('exit_date')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $absences = collect();
            $workers = collect();
        }

        $reasons = Reason::where('status', true)->orderBy('name', 'asc')->get();

        if (!$user->hasRole('asistencia')) {
            return Inertia::render('Assist/Absence/Index', compact('absences', 'workers', 'reasons'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(AbsenceRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('absences', 'public');
            }

            Absence::create($data);

            return redirect()->route('absence.index')->with('toast', ['Ausencia creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al crear la ausencia!', 'danger']);
        }
    }

    public function update(AbsenceRequest $request, Absence $absence): RedirectResponse
    {
        try {
            $user = auth()->user();

            // Permitir actualización si el usuario es administrador
            if (!$user->hasRole('administrador') && $absence->status !== 'Por aprobar') {
                return redirect()->back()->with('toast', ['No se puede actualizar la ausencia, ya que no está en estado "Por aprobar".', 'warning']);
            }

            $data = $request->validated();

            if ($request->hasFile('file')) {
                if ($absence->file) {
                    Storage::disk('public')->delete($absence->file);
                }
                $data['file'] = $request->file('file')->store('absences', 'public');
            } else {
                $data['file'] = $absence->file;
            }

            $absence->update($data);

            return redirect()->route('absence.index')->with('toast', ['Ausencia actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la ausencia!', 'danger']);
        }
    }

    public function destroy(Absence $absence): RedirectResponse
    {
        try {
            if ($absence->file) {
                Storage::disk('public')->delete($absence->file);
            }

            $absence->delete();

            return redirect()->route('absence.index')->with('toast', ['Ausencia eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar la ausencia, ya que tiene registros asociados!', 'danger']);
        }
    }

    public function download($file)
    {
        $filePath = public_path('/storage/absences/' . $file);

        if (file_exists($filePath)) {
            return ResponseFacade::download($filePath);
        }

        abort(404, 'Archivo no encontrado');
    }

    public function search(Request $request): Response
    {
        $user = auth()->user();
        $texto = $request->get('texto');

        if ($user->hasRole('administrador') || $user->hasRole('visualizador')) {
            $absences = Absence::with(['worker.pdv.zonal', 'reason'])
                ->where(function ($query) use ($texto) {
                    $query->whereHas('worker', function ($query) use ($texto) {
                        $query->where('name', 'like', '%' . $texto . '%')
                            ->orWhere('lastname', 'like', '%' . $texto . '%');
                    })
                        ->orWhereHas('reason', function ($query) use ($texto) {
                            $query->where('name', 'like', '%' . $texto . '%');
                        })
                        ->orWhereHas('worker.pdv', function ($query) use ($texto) {
                            $query->where('spot', 'like', '%' . $texto . '%');
                        })
                        ->orWhereHas('worker.pdv.zonal', function ($query) use ($texto) {
                            $query->where('name', 'like', '%' . $texto . '%');
                        })
                        ->orWhere('status', 'like', '%' . $texto . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->appends(['texto' => $texto]);

            $workers = Worker::whereNull('exit_date')
                ->orWhere('exit_date', '')
                ->orderBy('name', 'asc')
                ->get();
        } elseif ($user->hasRole('supervisor')) {
            $pdvId = $user->pdv_id;
            $absences = Absence::with(['worker.pdv.zonal', 'reason'])
                ->whereHas('worker.pdv', function ($query) use ($pdvId) {
                    $query->where('id', $pdvId);
                })
                ->where(function ($query) use ($texto) {
                    $query->whereHas('worker', function ($query) use ($texto) {
                        $query->where('name', 'like', '%' . $texto . '%')
                            ->orWhere('lastname', 'like', '%' . $texto . '%');
                    })
                        ->orWhereHas('reason', function ($query) use ($texto) {
                            $query->where('name', 'like', '%' . $texto . '%');
                        })
                        ->orWhereHas('worker.pdv', function ($query) use ($texto) {
                            $query->where('spot', 'like', '%' . $texto . '%');
                        })
                        ->orWhereHas('worker.pdv.zonal', function ($query) use ($texto) {
                            $query->where('name', 'like', '%' . $texto . '%');
                        })
                        ->orWhere('status', 'like', '%' . $texto . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->appends(['texto' => $texto]);

            $workers = Worker::where('pdv_id', $pdvId)
                ->whereNull('exit_date')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $absences = collect();
            $workers = collect();
        }

        $reasons = Reason::where('status', true)->orderBy('name', 'asc')->get();

        return Inertia::render('Assist/Absence/Index', compact('absences', 'workers', 'reasons', 'texto'));
    }
}
