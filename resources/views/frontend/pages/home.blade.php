@extends('frontend.layout.master')
@section('content')
    {{-- Slider --}}
    @include('frontend.sections.slider')


    {{-- Category --}}
    @include('frontend.sections.category')

    {{-- Flash sell --}}
    @include('frontend.sections.flash-sell')

    {{-- Brand --}}
    @include('frontend.sections.brand')
    {{-- Best product --}}
    @include('frontend.sections.best')

    {{-- top product --}}
    @include('frontend.sections.top-product')

    {{-- New Arrival product --}}
    @include('frontend.sections.new-arrival')
    {{-- Featured product --}}
    @include('frontend.sections.featured-product')
@endsection


@push('scripts')
    <script>
        $(".product").on("click", function() {
            const url = $(this).data("url");
            window.location.replace(url);
        });
    </script>
    <script>
        $(".add-to-wishlist").on("click", function() {
            const productId = $(this).data("id");
            $.ajax({
                type: "POST",
                url: "{{ route('user.profile.wishlist.add-to-wishlist') }}",
                data: {
                    product_id: productId
                },
                dataType: "json",
                success: function(response, textStatus, jqXHR) {
                    console.log(response);
                    // Not in wishlist
                    if (response.success == true) {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #22c55e, #16a34a)", // green

                        }).showToast();
                    }
                    // Already in wishlist
                    else {
                        Toastify({
                            text: response.message,
                            backgroundColor: "linear-gradient(to right, #ef4444, #b91c1c)", // red/danger
                        }).showToast();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Toastify({
                        text: "You need to login first",
                        backgroundColor: "linear-gradient(to right, #f59e42, #fbbf24)", // orange/yellow
                    }).showToast();
                }
            });
        });
    </script>
@endpush
