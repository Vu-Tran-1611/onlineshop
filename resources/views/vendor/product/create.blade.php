@extends('vendor.layout.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div> --}}
        </div>

        <div class="section-body">
            <h2 class="section-title">Add New Product</h2>

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Product Information</h4>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" action="{{ route('vendor.product.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">Image</label>
                                    <input name="thumb_image" type="file" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input value="{{ old('name') }}" name="name" type="text" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="">Category</label>
                                        <select name="category_id" class="form-control main_category">
                                            <option value=""> Select </option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="">Sub Category</label>
                                        <select name="sub_category_id" class="sub_category form-control">
                                            <option value=""> Select </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="">Product type</label>
                                    <select name="product_type" class="form-control">
                                        <option value=""> Select </option>
                                        <option value="new_arrival">New Arrival</option>
                                        <option value="top">Top Product</option>
                                        <option value="best">Best Product</option>
                                        <option value="featured">Featured Product</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Brands</label>
                                    <select name="brand_id" class="brand form-control">
                                        <option value=""> Select </option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Quantity</label>
                                    <input value="{{ old('qty') }}" name="qty" type="number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Short Description</label>
                                    <textarea value="{{ old('short_description') }}" class="summernote" name="short_description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Long Description</label>
                                    <textarea value="{{ old('long_description') }}" class="summernote" name="long_description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Video Link</label>
                                    <input value="{{ old('video_link') }}" name="video_link" type="text"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">SKU</label>
                                    <input value="{{ old('sku') }}" name="sku" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input value="{{ old('price') }}" name="price" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Offer Price</label>
                                    <input value="{{ old('offer_price') }}" name="offer_price" type="text"
                                        class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="">Offer Start Price</label>
                                        <input value="{{ old('offer_start_price') }}" name="offer_start_price"
                                            type="date" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="">Offer End Price</label>
                                        <input value="{{ old('offer_end_price') }}" name="offer_end_price" type="date"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">Seo Title</label>
                                    <input value="{{ old('seo_title') }}" name="seo_title" type="text"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control ">
                                        <option value="1"> Active </option>
                                        <option value="0"> Inactive </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Seo Description</label>
                                    <input value="{{ old('seo_description') }}" name="seo_description" type="text"
                                        class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                                <a href="{{ route('vendor.product.index') }}" class="ml-2 btn btn-danger text-white">
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
