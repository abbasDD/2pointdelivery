@extends('frontend.layouts.app')

@section('title', 'Privacy Policy')

@section('content')



    {{-- Privacy Policy --}}

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Privacy Policy</h2>
                    </div>
                    <div class="col-md-12">
                        {{-- Privacy Policy --}}
                        {!! $privacy_policy !!}
                    </div>
                </div>
            </div>
    </section>



    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')


@endsection
