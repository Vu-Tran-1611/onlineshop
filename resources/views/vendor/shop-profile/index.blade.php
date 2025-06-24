@extends('vendor.layout.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Shop Profile</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div> --}}
        </div>

        <div class="section-body">
            <h2 class="section-title">Shop profile</h2>

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Shop profile</h4>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" action="{{ route('vendor.shop-profile.store') }}"
                                method="POST">
                                @csrf
                                <img alt="{{ $profile->name }}" src="{{ asset($profile->banner) }}" width="500" />
                                <div class="form-group">
                                    <label for="">Banner</label>
                                    <input name="banner" type="file" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input name="name" value="{{ $profile->name }}" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input value="{{ $profile->phone }}" name="phone" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input value="{{ $profile->email }}" name="email" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <input value="{{ $profile->address }}" name="address" type="text"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">description</label>
                                    <textarea class="summernote" name="description">{{ $profile->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Facebook Link</label>
                                    <input value="{{ $profile->fb_link }}" name="fb_link" type="text"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Twiter Link</label>
                                    <input value="{{ $profile->tw_link }}" name="tw_link" type="text"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Instagram Link</label>
                                    <input value="{{ $profile->insta_link }}" name="insta_link" type="text"
                                        class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.slider.index') }}" class="ml-2 btn btn-info text-white"> Back </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
