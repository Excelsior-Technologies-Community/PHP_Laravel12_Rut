@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Citizens Management</h2>

    <div>

        <a href="{{ route('generator') }}"
            class="btn btn-success">

            Generate RUTs

        </a>

        <a href="{{ route('citizens.create') }}"
            class="btn btn-primary">

            Add Citizen

        </a>

    </div>

</div>

<div class="card mb-4">

    <div class="card-body">

        <form action="{{ route('search.rut') }}"
            method="POST">

            @csrf

            <div class="row">

                <div class="col-md-10">

                    <input type="text"
                        name="rut"
                        class="form-control"
                        placeholder="Search By RUT"
                        value="{{ old('rut') }}"
                        required>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">

                        Search

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>

            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>RUT</th>
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

            <td>

                <a href="{{ route('citizens.show',$citizen->id) }}"
                    class="btn btn-info btn-sm">

                    View

                </a>

                <a href="{{ route('citizens.edit',$citizen->id) }}"
                    class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form action="{{ route('citizens.destroy',$citizen->id) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete this record?')">

                        Delete

                    </button>

                </form>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="5"
                class="text-center">

                No Records Found

            </td>

        </tr>

        @endforelse

    </tbody>

</table>

@endsection