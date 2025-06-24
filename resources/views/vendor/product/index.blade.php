@php
    $role = Auth::user()->role;
@endphp
@extends(Auth::user()->role === 'admin' ? 'admin.layout.master' : 'vendor.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Product</div>
            </div> --}}
        </div>

        <div class="section-body">
            <h2 class="section-title">Product information</h2>

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Product Table</h4>
                            <div class="card-header-action">
                                <a href="{{ route('vendor.product.create') }}" class="btn btn-primary"> Create New</a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
