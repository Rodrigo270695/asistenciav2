<?php

namespace App\Http\Controllers;

use App\Exports\PdvExport;
use App\Exports\ZonalExport;
use App\Http\Requests\PdvRequest;
use App\Models\Horary;
use App\Models\Pdv;
use App\Models\Zonal;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class PdvController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        $pdvs = Pdv::with('zonal')->orderBy('id', 'desc')->paginate(7);
        $zonals = Zonal::where('status', true)->orderBy('name', 'asc')->get();
        $horaries = Horary::orderBy('name', 'asc')->get();
        if ($user->hasRole('administrador')) {
            return Inertia::render('Zonal/Pdv/Index', compact('pdvs', 'zonals', 'horaries'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(PdvRequest $request): RedirectResponse
    {
        try {
            Pdv::create($request->all());
            return redirect()->route('pdv.index')->with('toast', ['PDV creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(PdvRequest $request, Pdv $pdv): RedirectResponse
    {
        try {
            $pdv->update($request->all());
            return redirect()->route('pdv.index')->with('toast', ['PDV actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Pdv $pdv)
    {
        try {
            $pdv->delete();
            return redirect()->route('pdv.index')->with('toast', ['PDV eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El PDV no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el PDV!', 'danger']);
            }
        }
    }

    public function change(Pdv $pdv): RedirectResponse
    {
        $pdv->status = !$pdv->status;
        $pdv->save();
        return redirect()->route('pdv.index')->with('toast', ['Cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');

        $pdvs = Pdv::with('zonal')->where('spot', 'like', '%' . $texto . '%')
            ->orWhere('unit', 'like', '%' . $texto . '%')
            ->orWhereHas('zonal', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhere('status', $texto === 'activo' ? true : ($texto === 'inactivo' ? false : null))
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);
        $zonals = Zonal::where('status', true)->get();
        $horaries = Horary::orderBy('name', 'asc')->get();

        return Inertia::render('Zonal/Pdv/Index', compact('pdvs', 'texto', 'zonals', 'horaries'));
    }

    public function export(Request $request)
    {
        $texto = $request->get('texto');

        $pdvs = Pdv::with('zonal')->where('spot', 'like', '%' . $texto . '%')
            ->orWhere('unit', 'like', '%' . $texto . '%')
            ->orWhereHas('zonal', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhere('status', $texto === 'activo' ? true : ($texto === 'inactivo' ? false : null))
            ->orderBy("id", "asc")
            ->get();

        return Excel::download(new PdvExport($pdvs), 'pdvs.xlsx');
    }
}
