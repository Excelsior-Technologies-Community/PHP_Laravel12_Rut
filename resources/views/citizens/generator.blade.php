@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Generated RUTs</h2>

    <a href="{{ route('citizens.index') }}"
        class="btn btn-secondary">

        Back

    </a>

</div>

<div class="card">

    <div class="card-body">

        <table class="table table-bordered">

            <thead class="table-dark">

                <tr>

                    <th>#</th>
                    <th>Generated RUT</th>
                    <th>Type</th>

                </tr>

            </thead>

            <tbody>

                @foreach($ruts as $index => $rut)

                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td>{{ $rut }}</td>

                    <td>

                        @if($rut->isPerson())
                        Person
                        @elseif($rut->isCompany())
                        Company
                        @elseif($rut->isTemporal())
                        Temporal
                        @elseif($rut->isInvestor())
                        Investor
                        @else
                        Other
                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection