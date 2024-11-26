<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetailhoraryRequest;
use App\Models\Detailhorary;
use App\Models\Horary;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class DetailhoraryController extends Controller
{
    public function index(Horary $horary): Response
    {
        $user = auth()->user();
        $detailhoraries = $horary->detailhoraries;
        if ($user->hasRole('administrador')) {
            return Inertia::render('Zonal/Horary/Detail/Index', compact('detailhoraries', 'horary'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(DetailhoraryRequest $request, Horary $horary): RedirectResponse
    {
        try {
            foreach ($request->input('week') as $day) {
                $horary->detailhoraries()->create([
                'week' => $day,
                'hi' => $request->input('hi'),
                'rs' => $request->input('rs'),
                'ri' => $request->input('ri'),
                'hs' => $request->input('hs'),
                ]);
            }
            return redirect()->route('detailhorary.index', $horary)->with('toast', ['Detalles de horario creados exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(Request $request, Detailhorary $detailhorary): RedirectResponse
    {
        try {
            $detailhorary->update($request->only('week', 'hi', 'rs', 'ri', 'hs'));
            return redirect()->route('detailhorary.index', $detailhorary->horary)->with('toast', ['Detalle de horario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Detailhorary $detailhorary): RedirectResponse
    {
        $detailhorary->delete();
        return redirect()->route('detailhorary.index', $detailhorary->horary)->with('toast', ['Detalle de horario eliminado exitosamente!', 'success']);
    }
}
