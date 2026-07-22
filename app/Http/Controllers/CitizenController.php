<?php

namespace App\Http\Controllers;

use App\Imports\CitizensImport;
use App\Models\Citizen;
use App\Services\AiParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laragear\Rut\Rut;
use Laragear\Rut\Facades\Generator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CitizenController extends Controller
{
    public function index(Request $request)
    {
        $query = Citizen::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rut')) {
            $query->whereRut($request->rut);
        }

        if ($request->filled('type') && in_array($request->type, ['person', 'company', 'temporal', 'investor'])) {
            $query->where('rut_type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $sort = $request->get('sort', 'desc');

        $query->orderBy('created_at', $sort === 'asc' ? 'asc' : 'desc');

        $citizens = $query
            ->paginate(10)
            ->appends($request->except('page'));

        $totalCitizens = Citizen::count();
        $trashedCount = Citizen::onlyTrashed()->count();

        return view(
            'citizens.index',
            compact(
                'citizens',
                'totalCitizens',
                'trashedCount'
            )
        );
    }

    public function create()
    {
        return view('citizens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:citizens,email',
            'rut' => 'required|rut',
        ]);

        $rut = Rut::parse($request->rut);

        $type = 'other';
        if ($rut->isPerson()) $type = 'person';
        elseif ($rut->isCompany()) $type = 'company';
        elseif ($rut->isTemporal()) $type = 'temporal';
        elseif ($rut->isInvestor()) $type = 'investor';

        Citizen::create([
            'name' => $request->name,
            'email' => $request->email,
            'rut_num' => $rut->num,
            'rut_vd' => $rut->vd,
            'rut_type' => $type,
        ]);

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen created successfully.'
            );
    }

    public function show(Citizen $citizen)
    {
        $qrCode = QrCode::size(200)->generate($citizen->rut);

        return view(
            'citizens.show',
            compact('citizen', 'qrCode')
        );
    }

    public function edit(Citizen $citizen)
    {
        return view(
            'citizens.edit',
            compact('citizen')
        );
    }

    public function update(Request $request, Citizen $citizen)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'email' =>
                'required|email|unique:citizens,email,' .
                $citizen->id,

            'rut' => 'required|rut',
        ]);

        $rut = Rut::parse($request->rut);

        $type = 'other';
        if ($rut->isPerson()) $type = 'person';
        elseif ($rut->isCompany()) $type = 'company';
        elseif ($rut->isTemporal()) $type = 'temporal';
        elseif ($rut->isInvestor()) $type = 'investor';

        $citizen->update([
            'name' => $request->name,
            'email' => $request->email,
            'rut_num' => $rut->num,
            'rut_vd' => $rut->vd,
            'rut_type' => $type,
        ]);

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen updated successfully.'
            );
    }

    public function destroy(Citizen $citizen)
    {
        $citizen->delete();

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen deleted successfully.'
            );
    }

    public function generator()
    {
        $ruts = Generator::asPeople()->make(20);

        return view(
            'citizens.generator',
            compact('ruts')
        );
    }

    public function search(Request $request)
    {
        $request->validate([
            'rut' => 'required|min:7'
        ]);

        $citizens = Citizen::whereRut(
            $request->rut
        )->paginate(5);

        $totalCitizens = $citizens->total();

        return view(
            'citizens.index',
            compact(
                'citizens',
                'totalCitizens'
            )
        );
    }

    public function liveSearch(Request $request)
    {
        $search = $request->get('search');

        $citizens = Citizen::query()
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->when(strlen($search) >= 7, function ($query) use ($search) {
                      try {
                          $query->orWhereRut($search);
                      } catch (\Exception $e) {
                          // ignore invalid RUT format in search
                      }
                  });
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'rut_num', 'rut_vd', 'created_at']);

        return response()->json($citizens);
    }

    public function export()
    {
        $fileName = 'citizens.csv';

        $citizens = Citizen::all();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" =>
                "attachment; filename={$fileName}",
        ];

        $callback = function () use ($citizens) {

            $file = fopen(
                'php://output',
                'w'
            );

            fputcsv(
                $file,
                [
                    'ID',
                    'Name',
                    'Email',
                    'RUT',
                    'Created At'
                ]
            );

            foreach ($citizens as $citizen) {

                fputcsv(
                    $file,
                    [
                        $citizen->id,
                        $citizen->name,
                        $citizen->email,
                        $citizen->rut,
                        $citizen->created_at,
                    ]
                );
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            $headers
        );
    }

    public function dashboard()
    {
        $totalCitizens = Citizen::count();
        $trashedCount = Citizen::onlyTrashed()->count();
        $recentCitizens = Citizen::latest()->take(5)->get();

        $personCount = Citizen::where('rut_type', 'person')->count();

        $companyCount = Citizen::where('rut_type', 'company')->count();

        $temporalCount = Citizen::where('rut_type', 'temporal')->count();

        $investorCount = Citizen::where('rut_type', 'investor')->count();

        $chartData = [
            'labels' => ['Person', 'Company', 'Temporal', 'Investor'],
            'values' => [$personCount, $companyCount, $temporalCount, $investorCount],
        ];

        $monthlyData = Citizen::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->take(12)
            ->get();

        $monthlyChart = [
            'labels' => $monthlyData->pluck('month'),
            'values' => $monthlyData->pluck('count'),
        ];

        return view(
            'citizens.dashboard',
            compact(
                'totalCitizens',
                'trashedCount',
                'recentCitizens',
                'chartData',
                'monthlyChart',
                'personCount',
                'companyCount',
                'temporalCount',
                'investorCount',
            )
        );
    }

    public function trash()
    {
        $citizens = Citizen::onlyTrashed()
            ->latest()
            ->paginate(10);

        $totalCitizens = Citizen::count();
        $trashedCount = Citizen::onlyTrashed()->count();

        return view(
            'citizens.trash',
            compact('citizens', 'totalCitizens', 'trashedCount')
        );
    }

    public function restore($id)
    {
        $citizen = Citizen::onlyTrashed()->findOrFail($id);
        $citizen->restore();

        return back()->with('success', 'Citizen restored successfully.');
    }

    public function forceDelete($id)
    {
        $citizen = Citizen::onlyTrashed()->findOrFail($id);
        $citizen->forceDelete();

        return back()->with('success', 'Citizen permanently deleted.');
    }

    public function printCard($id)
    {
        $citizen = Citizen::findOrFail($id);
        $qrCode = QrCode::size(200)->generate($citizen->rut);

        $pdf = Pdf::loadView('citizens.card', compact('citizen', 'qrCode'));

        return $pdf->download("citizen-{$citizen->id}-card.pdf");
    }

    public function generateReport()
    {
        $totalCitizens = Citizen::count();
        $trashedCount = Citizen::onlyTrashed()->count();
        $recentCitizens = Citizen::latest()->take(10)->get();
        $allCitizens = Citizen::all();

        $personCount = Citizen::where('rut_type', 'person')->count();

        $companyCount = Citizen::where('rut_type', 'company')->count();

        $temporalCount = Citizen::where('rut_type', 'temporal')->count();

        $investorCount = Citizen::where('rut_type', 'investor')->count();

        $monthlyData = Citizen::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->take(12)
            ->get();

        $pdf = Pdf::loadView('citizens.report', compact(
            'totalCitizens',
            'trashedCount',
            'recentCitizens',
            'allCitizens',
            'personCount',
            'companyCount',
            'temporalCount',
            'investorCount',
            'monthlyData'
        ));

        return $pdf->download('citizen-report.pdf');
    }

    public function parseId(Request $request, AiParserService $parser)
    {
        $request->validate([
            'id_document' => 'required|image|max:5120',
        ]);

        try {
            $extracted = $parser->extractFromImage($request);

            return response()->json([
                'success' => true,
                'data' => $extracted,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
