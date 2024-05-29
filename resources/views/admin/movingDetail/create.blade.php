@extends('admin.layouts.app')

@section('title', 'Moving Config')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Add Moving Detail</h4>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openAddCategoryModal()">
                        Add Category
                    </button>
                </div>
            </div>
            <div class="section-body">

                @if (count($movingDetailCategories) > 0)
                    <form action="{{ route('admin.movingDetail.store') }}" method="POST">
                        @csrf
                        @include('admin.movingDetail.form')
                    </form>
                @else
                    {{-- Add category button to open modal --}}
                    <div class="col-md-12 text-center">
                        <h3 class="mb-1">No Category Found</h3>
                        <p>You need to add category first</p>
                        <button type="button" class="btn btn-primary btn-block" onclick="openAddCategoryModal()">
                            Add Category
                        </button>
                    </div>
                @endif


                {{-- Modal to add category --}}
                @include('admin.movingDetail.partials.category_modal')

            </div>
        </div>
    </section>

@endsection
