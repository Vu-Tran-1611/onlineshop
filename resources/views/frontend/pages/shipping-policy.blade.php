@extends('frontend.layout.master', ['title' => $title])
@section('content')
    {!! $content->content !!}
@endsection
@push('scripts')
    <script></script>
@endpush
