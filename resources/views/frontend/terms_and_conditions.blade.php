@extends('frontend.layouts.app')

@section('title', 'Terms and Conditions')

@section('content')



    {{-- Terms and Conditions --}}

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Terms and Conditions</h2>
                    </div>
                    <div class="col-md-12">
                        {{-- Terms and Conditions --}}
                        {!! $terms_and_conditions !!}
                    </div>
                </div>
            </div>
    </section>



    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


@endsection
