@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">
            Citizen Details
        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="250">Name</th>
                <td>{{ $citizen->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $citizen->email }}</td>
            </tr>

            <tr>
                <th>Formatted RUT</th>
                <td>{{ $citizen->rut }}</td>
            </tr>

            <tr>
                <th>RUT Number</th>
                <td>{{ $citizen->rut_num }}</td>
            </tr>

            <tr>
                <th>Verification Digit</th>
                <td>{{ $citizen->rut_vd }}</td>
            </tr>

        </table>

        <a href="{{ route('citizens.index') }}"
            class="btn btn-secondary">

            Back

        </a>

    </div>

</div>

@endsection