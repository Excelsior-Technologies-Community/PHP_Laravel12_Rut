@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Bulk Import Citizens</h2>

    <a href="{{ route('import.template') }}" class="btn btn-success">Download CSV Template</a>

</div>

<div class="row">
    <div class="col-md-8 offset-md-2">

        <div class="card">

            <div class="card-header">

                <h5>Upload CSV or Excel File</h5>

            </div>

            <div class="card-body">

                <p class="text-muted">Drag and drop your file here, or click to browse. Maximum file size: 10MB.</p>

                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="importDropzone">

                    @csrf

                    <div class="dz-message">

                        <h4>Drop files here or click to upload</h4>

                        <p class="text-muted">Supported formats: CSV, XLSX, XLS</p>

                    </div>

                </form>

                <div class="mt-3 text-center">

                    <a href="{{ route('citizens.index') }}" class="btn btn-secondary">Back</a>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')

<script>

Dropzone.options.importDropzone = false;

document.addEventListener('DOMContentLoaded', function() {

    new Dropzone('#importDropzone', {

        paramName: 'file',

        maxFilesize: 10,

        acceptedFiles: '.csv,.txt,.xlsx,.xls',

        addRemoveLinks: true,

        autoProcessQueue: true,

        init: function() {

            this.on('addedfile', function(file) {

                if (this.files.length > 1) {

                    this.removeFile(this.files[0]);

                }

            });

            this.on('success', function(file, response) {

                window.location.href = '{{ route("citizens.index") }}';

            });

            this.on('error', function(file, errorMessage) {

                if (typeof errorMessage === 'string') {

                    alert(errorMessage);

                }

            });

        },

    });

});

</script>

@endpush
