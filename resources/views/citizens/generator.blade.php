@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Generated RUTs</h2>

    <a href="{{ route('citizens.index') }}" class="btn btn-secondary">Back</a>

</div>

<div class="row">
    @foreach($ruts as $index => $rut)

        <div class="col-md-3 mb-4">

            <div class="card text-center h-100">

                <div class="card-body d-flex flex-column align-items-center">

                    {!! QrCode::size(120)->generate((string)$rut) !!}

                    <h6 class="mt-2 mb-1">{{ $rut }}</h6>

                    <span class="badge bg-primary">

                        @if($rut->isPerson()) Person
                        @elseif($rut->isCompany()) Company
                        @elseif($rut->isTemporal()) Temporal
                        @elseif($rut->isInvestor()) Investor
                        @else Other
                        @endif

                    </span>

                </div>

            </div>

        </div>

    @endforeach
</div>

@endsection
