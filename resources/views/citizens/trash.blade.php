@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Trash</h2>

    <a href="{{ route('citizens.index') }}" class="btn btn-primary">Back to Citizens</a>

</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h3>{{ $trashedCount }}</h3>
                <p class="mb-0">Deleted Citizens</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h3>{{ $totalCitizens }}</h3>
                <p class="mb-0">Total Citizens</p>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>

            <th>ID</th>

            <th>Name</th>

            <th>Email</th>

            <th>RUT</th>

            <th>Deleted At</th>

            <th width="220">Action</th>

        </tr>

    </thead>

    <tbody>

        @forelse($citizens as $citizen)

            <tr>

                <td>{{ $citizen->id }}</td>

                <td>{{ $citizen->name }}</td>

                <td>{{ $citizen->email }}</td>

                <td>{{ $citizen->rut }}</td>

                <td>{{ $citizen->deleted_at ? $citizen->deleted_at->format('Y-m-d H:i') : '-' }}</td>

                <td>

                    <form action="{{ route('citizens.restore', $citizen->id) }}" method="POST" class="d-inline">

                        @csrf
                        @method('POST')

                        <button class="btn btn-success btn-sm" onclick="return confirm('Restore this record?')">Restore</button>

                    </form>

                    <form action="{{ route('citizens.forceDelete', $citizen->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this record? This cannot be undone.');">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">Force Delete</button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6" class="text-center">No records in trash</td>

            </tr>

        @endforelse

    </tbody>

</table>

<div class="mt-3">

    @if ($citizens->lastPage() > 1)

        <nav>

            <ul class="pagination justify-content-center">

                @for ($i = 1; $i <= $citizens->lastPage(); $i++)

                    <li class="page-item {{ $citizens->currentPage() == $i ? 'active' : '' }}">

                        <a class="page-link" href="{{ $citizens->url($i) . '?' . http_build_query(request()->except('page')) }}">{{ $i }}</a>

                    </li>

                @endfor

            </ul>

        </nav>

    @endif

</div>

@endsection
