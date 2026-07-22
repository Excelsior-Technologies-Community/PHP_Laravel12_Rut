@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Dashboard</h2>

    <a href="{{ route('citizens.index') }}" class="btn btn-primary">View Citizens</a>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="card stat-card bg-primary text-white">

            <div class="card-body">

                <h3>{{ $totalCitizens }}</h3>

                <p class="mb-0">Total Citizens</p>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card bg-warning text-dark">

            <div class="card-body">

                <h3>{{ $trashedCount }}</h3>

                <p class="mb-0">In Trash</p>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card bg-success text-white">

            <div class="card-body">

                <h3>{{ $personCount + $companyCount + $temporalCount + $investorCount }}</h3>

                <p class="mb-0">Active RUTs</p>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card bg-info text-white">

            <div class="card-body">

                <h3>{{ $personCount }}</h3>

                <p class="mb-0">Persons</p>

            </div>

        </div>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="card stat-card bg-secondary text-white">

            <div class="card-body">

                <h3>{{ $companyCount }}</h3>

                <p class="mb-0">Companies</p>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card bg-dark text-white">

            <div class="card-body">

                <h3>{{ $temporalCount }}</h3>

                <p class="mb-0">Temporal</p>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card bg-danger text-white">

            <div class="card-body">

                <h3>{{ $investorCount }}</h3>

                <p class="mb-0">Investors</p>

            </div>

        </div>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-6">

        <div class="card">

            <div class="card-header bg-white">

                <h5 class="mb-0">RUT Type Distribution</h5>

            </div>

            <div class="card-body">

                <canvas id="rutTypeChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card">

            <div class="card-header bg-white">

                <h5 class="mb-0">Monthly Registrations</h5>

            </div>

            <div class="card-body">

                <canvas id="monthlyChart"></canvas>

            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header bg-white">

        <h5 class="mb-0">Recent Citizens</h5>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped mb-0">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>RUT</th>

                    <th>Created At</th>

                </tr>

            </thead>

            <tbody>

                @forelse($recentCitizens as $citizen)

                    <tr>

                        <td>{{ $citizen->id }}</td>

                        <td>{{ $citizen->name }}</td>

                        <td>{{ $citizen->email }}</td>

                        <td>{{ $citizen->rut }}</td>

                        <td>{{ $citizen->created_at->format('Y-m-d') }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center">No records found</td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    new Chart(document.getElementById('rutTypeChart'), {

        type: 'doughnut',

        data: {

            labels: {!! json_encode($chartData['labels']) !!},

            datasets: [{

                data: {!! json_encode($chartData['values']) !!},

                backgroundColor: [

                    '#0d6efd',

                    '#198754',

                    '#ffc107',

                    '#dc3545',

                ],

                borderWidth: 1,

            }],

        },

        options: {

            responsive: true,

            plugins: {

                legend: {

                    position: 'bottom',

                },

            },

        },

    });

    new Chart(document.getElementById('monthlyChart'), {

        type: 'bar',

        data: {

            labels: {!! json_encode($monthlyChart['labels']) !!},

            datasets: [{

                label: 'Registrations',

                data: {!! json_encode($monthlyChart['values']) !!},

                backgroundColor: '#0d6efd',

                borderWidth: 1,

            }],

        },

        options: {

            responsive: true,

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        stepSize: 1,

                    },

                },

            },

        },

    });

});

</script>

@endpush
