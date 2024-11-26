<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargeRequest;
use App\Models\Charge;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        if ($user->hasRole('administrador')) {
            $charges = Charge::orderBy('id', 'desc')->paginate(7);
            return Inertia::render('User/Charge/Index', compact('charges'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(ChargeRequest $request): RedirectResponse
    {
        try {
            Charge::create($request->validated());
            return redirect()->route('charge.index')->with('toast', ['Cargo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(ChargeRequest $request, Charge $charge): RedirectResponse
    {
        try {
            $charge->update($request->validated());
            return redirect()->route('charge.index')->with('toast', ['Cargo actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Charge $charge): RedirectResponse
    {
        try {
            $charge->delete();
            return redirect()->route('charge.index')->with('toast', ['Cargo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al eliminar el cargo!', 'danger']);
        }
    }

    public function change(Charge $charge): RedirectResponse
    {
        $charge->status = !$charge->status;
        $charge->save();
        return redirect()->route('charge.index')->with('toast', ['Cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');

        $charges = Charge::where('name', 'LIKE', '%'.$texto.'%')
        ->orWhere('status', $texto === 'activo' ? true : ($texto === 'inactivo' ? false : null))
        ->orderBy('id', 'desc')
        ->paginate(7)
        ->appends(['texto' => $texto]);

        return Inertia::render('User/Charge/Index', compact('charges', 'texto'));
    }
}
