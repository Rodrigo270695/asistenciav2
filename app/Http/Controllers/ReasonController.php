<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReasonRequest;
use App\Models\Reason;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReasonController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        if ($user->hasRole('administrador')) {
            $reasons = Reason::orderBy('id', 'desc')->paginate(7);
            return Inertia::render('Assist/Reason/Index', compact('reasons'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function create(): Response
    {
        return Inertia::render('Reason/Create');
    }

    public function store(ReasonRequest $request): RedirectResponse
    {
        try {
            Reason::create($request->validated());
            return redirect()->route('reason.index')->with('toast', ['Razón creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function edit(Reason $reason): Response
    {
        return Inertia::render('Reason/Edit', compact('reason'));
    }

    public function update(ReasonRequest $request, Reason $reason): RedirectResponse
    {
        try {
            $reason->update($request->validated());
            return redirect()->route('reason.index')->with('toast', ['Razón actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Reason $reason): RedirectResponse
    {
        try {
            $reason->delete();
            return redirect()->route('reason.index')->with('toast', ['Razón eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Error al eliminar la razón!', 'danger']);
        }
    }

    public function change(Reason $reason): RedirectResponse
    {
        $reason->status = !$reason->status;
        $reason->save();
        return redirect()->route('reason.index')->with('toast', ['Cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');

        $reasons = Reason::where('name', 'LIKE', '%'.$texto.'%')
        ->orWhere('status', $texto === 'activo' ? true : ($texto === 'inactivo' ? false : null))
        ->orderBy('id', 'desc')
        ->paginate(7)
        ->appends(['texto' => $texto]);

        return Inertia::render('Assist/Reason/Index', compact('reasons', 'texto'));
    }
}
