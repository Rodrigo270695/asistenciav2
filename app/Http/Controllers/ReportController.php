<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Http\Requests\ReportRequest;
use App\Models\Assist;
use App\Models\Pdv;
use App\Models\Zonal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        Carbon::setLocale('es');
        $startDate = $request->input('startDate', Carbon::now('America/Lima')->toDateString());
        $endDate = $request->input('endDate', Carbon::now('America/Lima')->toDateString());
        $selectedPdvs = $request->input('pdvs', []);
        $selectedZonals = $request->input('zonals', []);
        $query = $request->input('query', '');

        if (!empty($query)) {
            $assists = Assist::query()
                ->whereBetween('current_date', [$startDate, $endDate])
                ->when($query, function ($queryBuilder) use ($query) {
                    $queryBuilder->whereHas('worker', function ($q) use ($query) {
                        $q->where('name', 'like', "%$query%")
                            ->orWhere('lastname', 'like', "%$query%")
                            ->orWhere('num_document', 'like', "%$query%");
                    });
                })
                ->with(['worker.pdv.zonal'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->appends(['startDate' => $startDate, 'endDate' => $endDate, 'query' => $query, 'zonals' => $selectedZonals]);
        } else {
            $assists = Assist::query()
                ->whereBetween('current_date', [$startDate, $endDate])
                ->when(!empty($selectedPdvs), function ($queryBuilder) use ($selectedPdvs) {
                    $queryBuilder->whereHas('worker.pdv', function ($q) use ($selectedPdvs) {
                        $q->whereIn('id', $selectedPdvs);
                    });
                })
                ->with(['worker.pdv.zonal'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->appends(['startDate' => $startDate, 'endDate' => $endDate, 'pdvs' => $selectedPdvs]);
        }

        $zonals = Zonal::where('status', 1)
            ->orderBy('name')
            ->get();

        $pdvs = Pdv::where('status', 1)
            ->orderBy('spot')
            ->get();

        return Inertia::render('Report/Index', [
            'assists' => $assists,
            'zonals' => $zonals,
            'pdvs' => $pdvs,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedPdvs' => $selectedPdvs,
            'query' => $query,
            'selectedZonals' => $selectedZonals,
        ]);
    }

    public function export(Request $request)
    {
        try {

            Carbon::setLocale('es');
        $startDate = $request->input('startDate', Carbon::now('America/Lima')->toDateString());
        $endDate = $request->input('endDate', Carbon::now('America/Lima')->toDateString());
        $selectedPdvs = $request->input('pdvs', []);
        $query = $request->input('query', '');

        $assistsQuery = Assist::query()
            ->whereBetween('current_date', [$startDate, $endDate])
            ->with(['worker.pdv.zonal']);

        if (!empty($query) && $query !== 'null') {
            $assistsQuery->whereHas('worker', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('lastname', 'like', "%$query%")
                    ->orWhere('num_document', 'like', "%$query%");
            });
        }

        if (!empty($selectedPdvs)) {
            $assistsQuery->whereHas('worker.pdv', function ($q) use ($selectedPdvs) {
                $q->whereIn('id', $selectedPdvs);
            });
        }

        $assists = $assistsQuery->get();

        $currentTime = Carbon::now('America/Lima');
        return Excel::download(new ReportExport($assists), 'reporte_asistencias_'.$currentTime->toDateString().'.xlsx');

        } catch (Exception $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }

    }
}
