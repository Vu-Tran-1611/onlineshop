@extends('frontend.layout.master')
@section('content')
    {{-- Slider --}}
    @include('frontend.sections.slider')

    {{-- Category --}}
    @include('frontend.sections.category')

    {{-- Matrix Factorization Recommended Products --}}
    @include('frontend.sections.matrix-factorization-recommended-for-you')

    {{-- LightGCN Recommended Products --}}
    @include('frontend.sections.light-gcn-recommended-for-you')


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
@endpush
