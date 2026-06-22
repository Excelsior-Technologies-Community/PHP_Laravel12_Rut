@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Create Citizen</h3>

    </div>

    <div class="card-body">

        @if($errors->any())

        <div class="alert alert-danger">

            <ul>

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('citizens.store') }}"
            method="POST">

            @csrf

            <div class="mb-3">

                <label>Name</label>

                <input type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}">

            </div>

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}">

            </div>

            <div class="mb-3">

                <label>RUT</label>

                <input type="text"
                    name="rut"
                    class="form-control"
                    placeholder="Enter valid RUT"
                    value="{{ old('rut') }}">

                <small class="text-muted">
                    Example: Use a valid RUT generated from the Generator page.
                </small>

            </div>

            <button class="btn btn-success">

                Save Citizen

            </button>

            <a href="{{ route('citizens.index') }}"
                class="btn btn-secondary">

                Back

            </a>

        </form>

    </div>

</div>

@endsection