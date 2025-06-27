@extends('frontend.layout.master', ['title' => $title])
@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="text-8xl font-extrabold text-gray-400 mb-4">404</div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Page Not Found</h1>
        <p class="text-gray-600 mb-6">Sorry, the page you are looking for does not exist or has been moved.</p>
        <a href="{{ url('/') }}" class="px-6 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 transition">
            Go to Homepage
        </a>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
