@extends('vendor.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Profile</div>
            </div> --}}
        </div>
        <div class="section-body">
            <h2 class="section-title">Hi, {{ $user->username }}</h2>
            <p class="section-lead">
                Change information about yourself on this page.
            </p>
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card profile-widget">

                        <img alt="image" src="{{ asset($user->image) }}"
                            class="shadow-xl rounded-circle profile-widget-picture">

                        <div class="profile-widget-items ">
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Name</div>
                                <div class="profile-widget-item-value">{{ $user->name }}</div>
                            </div>
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">username</div>
                                <div class="profile-widget-item-value">{{ $user->username }}</div>
                            </div>
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Email</div>
                                <div class="profile-widget-item-value">{{ $user->email }}</div>
                            </div>
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Phone</div>
                                <div class="profile-widget-item-value">{{ $user->phone }}</div>
                            </div>
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Role</div>
                                <div class="profile-widget-item-value">Vendor</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form enctype="multipart/form-data" action="{{ route('vendor.profile-update') }}" method="post"
                            class="needs-validation" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Edit Profile</h4>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Save Changes</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 mb-3">
                                        <img width="200" src="{{ asset("$user->image") }}" alt="image" />
                                    </div>
                                    <div class="form-group col-12">
                                        <input class="form-control" name="image" type="file" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Name</label>
                                        <input name="name" type="text" class="form-control"
                                            value="{{ $user->name }}" required="">

                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control"
                                            value="{{ $user->username }}">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label>Email</label>
                                        <input name="email" type="email" class="form-control"
                                            value="{{ $user->email }}" required="">

                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Phone</label>
                                        <input type="tel" name="phone" class="form-control"
                                            value="{{ $user->phone }}">
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Role</label>
                                        <input type="text" disabled name="phone" class="form-control" value="Vendor">
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="form-group col-12">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control summernote-simple">
                                    {{ $user->address }}
                                </textarea>

                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form enctype="multipart/form-data" action="{{ route('vendor.password-update') }}" method="post"
                            class="needs-validation" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Update Password</h4>

                            </div>

                            <div class="card-body">
                                <div class="row">


                                    <div class="form-group col-md-12 col-12">
                                        <label>Current Password</label>
                                        <input name="current_password" type="password" class="form-control"
                                            placeholder="Enter your current password" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the password
                                        </div>

                                    </div>
                                    <div class="form-group col-md-12 col-12">
                                        <label>New Password</label>
                                        <input name="password" type="password" class="form-control"
                                            placeholder="Enter new password" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the password
                                        </div>

                                    </div>
                                    <div class="form-group col-md-12 col-12">
                                        <label>Password Confirmation</label>
                                        <input name="password_confirmation" type="password"
                                            placeholder="Password confirmation" class="form-control" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the password confirmation
                                        </div>

                                    </div>


                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
