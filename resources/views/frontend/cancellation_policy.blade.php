@extends('frontend.layouts.app')

@section('title', 'Cancellation Policy')

@section('content')



    {{-- Cancellation Policy --}}

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Cancellation Policy</h2>
                    </div>
                    <div class="col-md-12">
                        {{-- Cancellation Policy --}}
                        {!! $cancellation_policy !!}
                    </div>
                </div>
            </div>
    </section>



    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


@endsection
