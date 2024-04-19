@extends('admin.layouts.app')

@section('title', 'Tax Settings')

@section('content')

    <div class="container">
        <h5>Tax Settings</h5>
        <div class="card">
            <div class="card-body">
                <div id="taxSettingTable">
                    @include('admin.settings.partials.tax_list')
                </div>
            </div>
        </div>
    </div>


@endsection
