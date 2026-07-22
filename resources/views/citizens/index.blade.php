@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Citizens Management</h2>

    <div>

        <a href="{{ route('citizens.export') }}" class="btn btn-dark">

            Export CSV

        </a>

        <a href="{{ route('import.create') }}" class="btn btn-info">

            Bulk Import

        </a>

        <a href="{{ route('generator') }}" class="btn btn-success">

            Generate RUTs

        </a>

        <a href="{{ route('citizens.create') }}" class="btn btn-primary">

            Add Citizen

        </a>

    </div>

</div>

<!-- Advanced Filters -->
<div class="card mb-3">

    <div class="card-body">

        <form action="{{ route('citizens.index') }}" method="GET" id="filterForm">

            <div class="row g-3">

                <div class="col-md-3">

                    <input type="text" name="search" class="form-control" placeholder="Search Name or Email"
                        value="{{ request('search') }}">

                </div>

                <div class="col-md-2">

                    <input type="text" name="rut" class="form-control" placeholder="RUT" value="{{ request('rut') }}">

                </div>

                <div class="col-md-2">

                    <select name="type" class="form-select">

                        <option value="">All Types</option>

                        <option value="person" {{ request('type') == 'person' ? 'selected' : '' }}>Person</option>

                        <option value="company" {{ request('type') == 'company' ? 'selected' : '' }}>Company</option>

                        <option value="temporal" {{ request('type') == 'temporal' ? 'selected' : '' }}>Temporal</option>

                        <option value="investor" {{ request('type') == 'investor' ? 'selected' : '' }}>Investor</option>

                    </select>

                </div>

                <div class="col-md-2">

                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From">

                </div>

                <div class="col-md-2">

                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To">

                </div>

                <div class="col-md-1">

                    <button type="submit" class="btn btn-primary w-100">Filter</button>

                </div>

            </div>

        </form>

    </div>

</div>

<!-- Ajax Live Search -->
<div class="card mb-3">

    <div class="card-body">

        <div class="input-group">

            <input type="text" id="liveSearch" class="form-control" placeholder="Type to live search by RUT, Name or Email..." autocomplete="off">

            <button class="btn btn-outline-secondary" type="button">
                <span id="searchSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>

        </div>

        <div id="liveSearchResults" class="mt-3" style="display:none;">

            <div class="list-group" id="resultsList"></div>

        </div>

    </div>

</div>

<!-- Bulk Actions -->
<div class="d-flex justify-content-between align-items-center mb-2">

    <div class="form-check">

        <input class="form-check-input" type="checkbox" id="selectAll">
        <label class="form-check-label" for="selectAll">Select All</label>

    </div>

    <div>

        <form action="{{ route('citizens.export') }}" method="GET" class="d-inline">

            <input type="hidden" name="ids" id="exportIds">

            <button type="submit" class="btn btn-sm btn-outline-primary" id="bulkExportBtn" disabled>

                Export Selected

            </button>

        </form>

    </div>

</div>

<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>

            <th width="40"><input type="checkbox" id="masterCheckbox"></th>

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

                <td><input type="checkbox" class="row-checkbox" value="{{ $citizen->id }}"></td>

                <td>{{ $citizen->id }}</td>

                <td>{{ $citizen->name }}</td>

                <td>{{ $citizen->email }}</td>

                <td>{{ $citizen->rut }}</td>

                <td>

                    <a href="{{ route('citizens.show', $citizen->id) }}" class="btn btn-info btn-sm">View</a>

                    <a href="{{ route('citizens.edit', $citizen->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <a href="{{ route('citizens.card', $citizen->id) }}" class="btn btn-dark btn-sm">Card</a>

                    <form action="{{ route('citizens.destroy', $citizen->id) }}" method="POST" class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6" class="text-center">No Records Found</td>

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

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    const liveSearch = document.getElementById('liveSearch');

    const resultsList = document.getElementById('resultsList');

    const resultsContainer = document.getElementById('liveSearchResults');

    const spinner = document.getElementById('searchSpinner');

    let debounceTimer;

    const masterCheckbox = document.getElementById('masterCheckbox');

    const selectAll = document.getElementById('selectAll');

    const bulkExportBtn = document.getElementById('bulkExportBtn');

    const exportIds = document.getElementById('exportIds');

    liveSearch.addEventListener('input', function() {

        clearTimeout(debounceTimer);

        const query = this.value.trim();

        if (query.length < 2) {

            resultsContainer.style.display = 'none';

            return;

        }

        debounceTimer = setTimeout(() => {

            spinner.classList.remove('d-none');

            fetch(`{{ route('citizens.liveSearch') }}?search=${encodeURIComponent(query)}`, {

                headers: {

                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },

            })

            .then(response => response.json())

            .then(data => {

                spinner.classList.add('d-none');

                resultsList.innerHTML = '';

                if (data.length === 0) {

                    resultsList.innerHTML = '<div class="list-group-item">No results found</div>';

                } else {

                    data.forEach(citizen => {

                        const item = document.createElement('a');

                        item.href = `{{ url('citizens') }}/${citizen.id}`;

                        item.className = 'list-group-item list-group-item-action';

                        item.innerHTML = `<strong>${citizen.name}</strong> - ${citizen.email} <span class="text-muted float-end">${citizen.rut}</span>`;

                        resultsList.appendChild(item);

                    });

                }

                resultsContainer.style.display = 'block';

            })

            .catch(() => {

                spinner.classList.add('d-none');

            });

        }, 300);

    });

    function updateBulkExport() {

        const checkboxes = document.querySelectorAll('.row-checkbox:checked');

        exportIds.value = Array.from(checkboxes).map(cb => cb.value).join(',');

        bulkExportBtn.disabled = checkboxes.length === 0;

    }

    document.querySelectorAll('.row-checkbox').forEach(cb => {

        cb.addEventListener('change', updateBulkExport);

    });

    if (masterCheckbox) {

        masterCheckbox.addEventListener('change', function() {

            document.querySelectorAll('.row-checkbox').forEach(cb => {

                cb.checked = this.checked;

            });

            updateBulkExport();

        });

    }

    if (selectAll) {

        selectAll.addEventListener('change', function() {

            document.querySelectorAll('.row-checkbox').forEach(cb => {

                cb.checked = this.checked;

            });

            updateBulkExport();

        });

    }

});

</script>

@endpush
