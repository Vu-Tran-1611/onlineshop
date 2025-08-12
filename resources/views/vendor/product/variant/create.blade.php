@extends('vendor.layout.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Table</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Product Variant</h2>

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Product Variant</h4>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data"
                                action="{{ route('vendor.product.variant.store', $product->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">Product Name</label>
                                    <input value="{{ $product->name }}" name="productID" type="text" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input value="{{ old('name') }}" name="name" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Is Swipable</label>
                                    <select name="is_swipe" class="form-control">
                                        <option value="1"> Active </option>
                                        <option value="0"> Inactive </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1"> Active </option>
                                        <option value="0"> Inactive </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                                <a href="{{ route('vendor.product.variant.index', $product->id) }}"
                                    class="ml-2 btn btn-danger text-white">
                                    Back
                                </a>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
