<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Laragear\Rut\Rut;
use Laragear\Rut\Facades\Generator;

class CitizenController extends Controller
{
    /**
     * Display all citizens
     */
    public function index()
    {
        $citizens = Citizen::latest()->get();

        return view(
            'citizens.index',
            compact('citizens')
        );
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('citizens.create');
    }

    /**
     * Store citizen
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:citizens,email',
            'rut'   => 'required|rut',
        ]);

        $rut = Rut::parse($request->rut);

        Citizen::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'rut_num' => $rut->num,
            'rut_vd'  => $rut->vd,
        ]);

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen created successfully.'
            );
    }

    /**
     * Show citizen details
     */
    public function show(Citizen $citizen)
    {
        return view(
            'citizens.show',
            compact('citizen')
        );
    }

    /**
     * Edit citizen
     */
    public function edit(Citizen $citizen)
    {
        return view(
            'citizens.edit',
            compact('citizen')
        );
    }

    /**
     * Update citizen
     */
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
            'name'    => $request->name,
            'email'   => $request->email,
            'rut_num' => $rut->num,
            'rut_vd'  => $rut->vd,
        ]);

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen updated successfully.'
            );
    }

    /**
     * Delete citizen
     */
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

    /**
     * Generate random RUTs
     */
    public function generator()
    {
        $ruts = Generator::asPeople()->make(20);

        return view(
            'citizens.generator',
            compact('ruts')
        );
    }

    /**
     * Search by RUT
     */
    public function search(Request $request)
    {
        $request->validate([
            'rut' => 'required|min:7'
        ]);

        $citizens = Citizen::whereRut(
            $request->rut
        )->get();


        return view(
            'citizens.index',
            compact('citizens')
        );
    }
}
