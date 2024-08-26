@extends('client.layouts.app')

@section('title', 'Adress Book ')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Edit Address Book</h4>
                </div>
            </div>
            <div class="section-body">
                <form action="{{ route('client.addressBook.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Hidden filed for id of addressBook --}}
                    <input type="hidden" name="id" value="{{ $addressBook->id }}">
                    @include('client.addressBooks.form')
                </form>

            </div>
        </div>
    </section>

@endsection
