@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Citizen Details</h4>
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

                    <tr>
                        <th>Created At</th>
                        <td>{{ $citizen->created_at->format('Y-m-d H:i') }}</td>
                    </tr>

                </table>

                <div class="mt-3">

                    <a href="{{ route('citizens.index') }}" class="btn btn-secondary">Back</a>

                    <a href="{{ route('citizens.edit', $citizen->id) }}" class="btn btn-warning">Edit</a>

                    <a href="{{ route('citizens.card', $citizen->id) }}" class="btn btn-dark" target="_blank">Print Card</a>

                    <a href="{{ route('citizens.report') }}" class="btn btn-danger">Full Report</a>

                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">RUT QR Code</h5>
            </div>
            <div class="card-body text-center qr-section">

                {!! $qrCode !!}

                <p class="mt-2 text-muted">{{ $citizen->rut }}</p>

            </div>
        </div>
    </div>
</div>

@endsection
