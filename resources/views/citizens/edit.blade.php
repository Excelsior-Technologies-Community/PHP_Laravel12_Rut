@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Edit Citizen</h3>

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



        <form action="{{ route('citizens.update',$citizen->id) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="mb-3">

                <label>Name</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name',$citizen->name) }}">

            </div>



            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email',$citizen->email) }}">

            </div>



            <div class="mb-3">

                <label>RUT</label>

                <input type="text"
                       name="rut"
                       class="form-control"
                       value="{{ old('rut',$citizen->rut) }}">


                <small class="text-muted">
                    Enter a valid generated RUT
                </small>

            </div>



            <button class="btn btn-primary">

                Update Citizen

            </button>


            <a href="{{ route('citizens.index') }}"
               class="btn btn-secondary">

                Back

            </a>


        </form>


    </div>

</div>


@endsection