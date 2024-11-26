<?php

namespace App\Http\Controllers;

use App\Exports\HoraryExport;
use App\Http\Requests\HoraryRequest;
use App\Models\Detailhorary;
use App\Models\Horary;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class HoraryController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        $horaries = Horary::orderBy('id', 'desc')->paginate(10);
        if ($user->hasRole('administrador')) {
            return Inertia::render('Zonal/Horary/Index', compact('horaries'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(HoraryRequest $request): RedirectResponse
    {
        try {
            Horary::create($request->validated());
            return redirect()->route('horary.index')->with('toast', ['Horarios creados exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(HoraryRequest $request, Horary $horary): RedirectResponse
    {
        try {
            $horary->update($request->validated());
            return redirect()->route('horary.index')->with('toast', ['Horario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Horary $horary)
    {
        try {
            $horary->delete();
            return redirect()->route('horary.index')->with('toast', ['Horario eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Horario no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el Horario!', 'danger']);
            }
        }
    }

    public function change(Horary $horary): RedirectResponse
    {
        $horary->status = !$horary->status;
        $horary->save();
        return redirect()->route('horary.index')->with('toast', ['Cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');

        $horaries = Horary::where('name', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Zonal/Horary/Index', compact('horaries', 'texto'));
    }

    public function export(Request $request)
    {
        $texto = $request->get('texto');

        $detailHoraries = Detailhorary::with('horary')
            ->whereHas('horary', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->get();

        return Excel::download(new HoraryExport($detailHoraries), 'horarios.xlsx');
    }
}
