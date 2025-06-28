@extends('admin.layout.master')

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
            <h2 class="section-title">Brand</h2>

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Brand</h4>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data"
                                action="{{ route('admin.top-banner.update', $topBanner->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="">label</label>
                                    <input name="label" type="text" class="form-control"
                                        value="{{ $topBanner->label }}">
                                </div>
                                <div class="form-group">
                                    <label for="">text</label>
                                    <input name="text" type="text" class="form-control"
                                        value="{{ $topBanner->text }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Link</label>
                                    <input name="link" type="text" class="form-control"
                                        value="{{ $topBanner->link }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $topBanner->is_active == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $topBanner->is_active == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Start Date</label>
                                    <input type="date" name="start_date" class="form-control datepicker"
                                        value="{{ $topBanner->start_date ? \Carbon\Carbon::parse($topBanner->start_date)->format('Y-m-d') : '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="">End Date</label>
                                    <input type="date" name="end_date" class="form-control datepicker"
                                        value="{{ $topBanner->end_date ? \Carbon\Carbon::parse($topBanner->end_date)->format('Y-m-d') : '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.top-banner.index') }}" class="ml-2 btn btn-danger text-white">
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
