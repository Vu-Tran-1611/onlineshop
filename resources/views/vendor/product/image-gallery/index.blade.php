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
            <h1>Product Image Galleries</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Product Image Gallery</div>
            </div> --}}
        </div>

        <a href="{{ $route }}"> <button class="btn btn-primary mb-3">Back </button></a>
        <div class="section-body">

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Upload Image</h4>

                        </div>
                        <div class="card-body">
                            @php
                                $route = route("$role.product.image-gallery.store", $product->id);
                            @endphp
                            <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" multiple name="images[]" />
                                </div>
                                <button class="btn btn-primary">Upload All</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h4>Product Image Galleries</h4>
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
    <script>
        $("body").on("change", ".input", function() {
            input = $(this).val();
            url = $(this).data("url");
            $.ajax({
                type: "PUT",
                url: url,
                data: {
                    input: input
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 'success') {
                        Toastify({
                            text: response.message,
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            }
                        }).showToast();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("error");
                }
            });
        });
    </script>
@endpush
