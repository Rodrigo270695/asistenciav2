<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerRequest;
use App\Models\Charge;
use App\Models\Worker;
use App\Models\Pdv;
use App\Models\Zonal;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkerController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        if ($user->hasRole('administrador')) {
            $workers = Worker::with('pdv.zonal', 'charge')->orderBy('id', 'desc')->paginate(7);
            $pdvs = Pdv::with('zonal')->where('status', true)->orderBy('spot', 'asc')->get();
            $charges = Charge::where('status', true)->orderBy('name', 'asc')->get();
            return Inertia::render('User/Worker/Index', compact('workers', 'pdvs', 'charges'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(WorkerRequest $request): RedirectResponse
    {
        try {
            Worker::create($request->validated());
            return redirect()->route('worker.index')->with('toast', ['Trabajador creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(WorkerRequest $request, Worker $worker): RedirectResponse
    {
        try {
            $worker->update($request->validated());
            return redirect()->route('worker.index')->with('toast', ['Trabajador actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Worker $worker)
    {
        try {
            $worker->delete();
            return redirect()->route('worker.index')->with('toast', ['Trabajador eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El trabajador no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el trabajador!', 'danger']);
            }
        }
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');

        $workers = Worker::with('pdv.zonal', 'charge')
            ->where('name', 'like', '%' . $texto . '%')
            ->orWhere('lastname', 'like', '%' . $texto . '%')
            ->orWhere('document_type', 'like', '%' . $texto . '%')
            ->orWhere('num_document', 'like', '%' . $texto . '%')
            ->orWhere('email', 'like', '%' . $texto . '%')
            ->orWhereHas('pdv', function ($query) use ($texto) {
                $query->where('spot', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('charge', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('pdv.zonal', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $pdvs = Pdv::with('zonal')->where('status', true)->orderBy('spot', 'asc')->get();
        $charges = Charge::where('status', true)->orderBy('name', 'asc')->get();

        return Inertia::render('User/Worker/Index', compact('workers', 'texto', 'pdvs', 'charges'));
    }
}
