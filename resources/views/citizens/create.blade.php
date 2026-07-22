@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8">
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

                <form action="{{ route('citizens.store') }}" method="POST" id="createCitizenForm">

                    @csrf

                    <div class="mb-3">

                        <label>Name</label>

                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="nameInput">

                    </div>

                    <div class="mb-3">

                        <label>Email</label>

                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="emailInput">

                    </div>

                    <div class="mb-3">

                        <label>RUT</label>

                        <input type="text" name="rut" class="form-control" placeholder="Enter valid RUT" value="{{ old('rut') }}" id="rutInput">

                        <small class="text-muted">Example: Use a valid RUT generated from the Generator page.</small>

                    </div>

                    <button type="submit" class="btn btn-success">Save Citizen</button>

                    <a href="{{ route('citizens.index') }}" class="btn btn-secondary">Back</a>

                </form>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Extract from ID Card</h5>
            </div>
            <div class="card-body">

                <p class="text-muted small">Upload a Chilean ID card image to auto-fill the form.</p>

                <form id="parseIdForm">

                    @csrf

                    <div class="mb-3">

                        <input type="file" class="form-control" name="id_document" id="idDocument" accept="image/*" required>

                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="parseBtn">

                        <span id="parseSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>

                        Extract Data

                    </button>

                </form>

                <div id="parseResult" class="mt-3" style="display:none;">

                    <div class="alert alert-success">

                        <strong>Extracted Data:</strong>

                        <ul class="mb-0 mt-2">

                            <li>Name: <span id="extractedName"></span></li>

                            <li>RUT: <span id="extractedRut"></span></li>

                        </ul>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    const parseForm = document.getElementById('parseIdForm');

    const parseBtn = document.getElementById('parseBtn');

    const parseSpinner = document.getElementById('parseSpinner');

    const parseResult = document.getElementById('parseResult');

    const nameInput = document.getElementById('nameInput');

    const rutInput = document.getElementById('rutInput');

    parseForm.addEventListener('submit', function(e) {

        e.preventDefault();

        const formData = new FormData(parseForm);

        parseSpinner.classList.remove('d-none');

        fetch('{{ route("parse.id") }}', {

            method: 'POST',

            body: formData,

            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',

            },

        })

        .then(response => response.json())

        .then(data => {

            parseSpinner.classList.add('d-none');

            if (data.success) {

                nameInput.value = data.data.name || '';

                rutInput.value = data.data.rut || '';

                parseResult.style.display = 'block';

            } else {

                alert(data.message || 'Failed to extract data.');

            }

        })

        .catch(() => {

            parseSpinner.classList.add('d-none');

            alert('An error occurred while parsing the document.');

        });

    });

});

</script>

@endpush
