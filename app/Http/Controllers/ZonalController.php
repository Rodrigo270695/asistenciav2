<?php

namespace App\Http\Controllers;

use App\Exports\ZonalExport;
use App\Http\Requests\ZonalRequest;
use App\Models\Zonal;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ZonalController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        $zonals = Zonal::orderBy('id', 'desc')->paginate(7);
        if ($user->hasRole('administrador')) {
            return Inertia::render('Zonal/Zonal/Index', compact('zonals'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(ZonalRequest $request): RedirectResponse
    {
        try {
            Zonal::create($request->validated());
            return redirect()->route('zonal.index')->with('toast', ['Zonal creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(ZonalRequest $request, Zonal $zonal): RedirectResponse
    {
        try {
            $zonal->update($request->validated());
            return redirect()->route('zonal.index')->with('toast', ['Zonal actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Zonal $zonal)
    {
        try {
            $zonal->delete();
            return redirect()->route('zonal.index')->with('toast', ['Zonal eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Zonal no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el Zonal!', 'danger']);
            }
        }
    }

    public function change(Zonal $zonal): RedirectResponse
    {
        $zonal->status = !$zonal->status;
        $zonal->save();
        return redirect()->route('zonal.index')->with('toast', ['Cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');

        $zonals = Zonal::where('name', 'like', '%' . $texto . '%')
            ->orWhere('status', $texto === 'activo' ? true : ($texto === 'inactivo' ? false : null))
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Zonal/Zonal/Index', compact('zonals', 'texto'));
    }

    public function export(Request $request)
    {
        $texto = $request->get('texto');

        $zonals = Zonal::query()
            ->when($texto, function ($query, $texto) {
                $query->where('name', 'like', '%' . $texto . '%')
                    ->orWhere('status', $texto === 'activo' ? true : ($texto === 'inactivo' ? false : null));
            })
            ->orderBy("id", "asc")
            ->get();

        return Excel::download(new ZonalExport($zonals), 'zonales.xlsx');
    }
}
