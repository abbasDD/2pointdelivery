@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Hero Section --}}
    @include('frontend.includes.hero')

    {{-- About Us Section  --}}
    @include('frontend.includes.about')

    {{-- How It Works Section  --}}
    @include('frontend.includes.howitworks')

    {{-- Testimonials --}}
    @include('frontend.includes.testimonials')

    {{-- Global Reach --}}
    @include('frontend.includes.globalreach')

    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')

@endsection
