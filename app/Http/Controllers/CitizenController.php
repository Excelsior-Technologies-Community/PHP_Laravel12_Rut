<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Laragear\Rut\Rut;
use Laragear\Rut\Facades\Generator;

class CitizenController extends Controller
{
    public function index()
    {
        $citizens = Citizen::oldest()->paginate(4);

        $totalCitizens = Citizen::count();

        return view(
            'citizens.index',
            compact(
                'citizens',
                'totalCitizens'
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

        Citizen::create([
            'name' => $request->name,
            'email' => $request->email,
            'rut_num' => $rut->num,
            'rut_vd' => $rut->vd,
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
        return view(
            'citizens.show',
            compact('citizen')
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

        $citizen->update([
            'name' => $request->name,
            'email' => $request->email,
            'rut_num' => $rut->num,
            'rut_vd' => $rut->vd,
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

    public function searchCitizen(Request $request)
    {
        $search = $request->search;

        $citizens = Citizen::query()
            ->when(
                $search,
                function ($query) use ($search) {

                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );
                }
            )
            ->latest()
            ->paginate(5);

        $totalCitizens = $citizens->total();

        return view(
            'citizens.index',
            compact(
                'citizens',
                'totalCitizens'
            )
        );
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
                    'RUT'
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
}