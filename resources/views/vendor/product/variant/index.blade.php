@php
    $route = route('vendor.product.index');
    $role = Auth::user()->role;
    if ($role == 'admin') {
        $route = route('admin.product_from_vendor.index');
    }
@endphp
@extends(Auth::user()->role === 'admin' ? 'admin.layout.master' : 'vendor.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product Variant</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Product Variant</div>
            </div> --}}
        </div>
        <a class="btn btn-primary" href="{{ $route }}"><i class="fa-solid fa-backward"></i> </a>
        <div class="section-body">
            <h2 class="section-title">{{ $product->name }}</h2>

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Product Variant Table</h4>
                            @if ($role != 'admin')
                                <div class="card-header-action">
                                    <a href="{{ route('vendor.product.variant.create', $product->id) }}"
                                        class="btn btn-primary">
                                        Create New</a>
                                </div>
                            @endif
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
