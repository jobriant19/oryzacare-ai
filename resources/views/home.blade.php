@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}?v=7">
    <link rel="stylesheet" href="{{ asset('css/recent.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/diseases.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/cara.css') }}?v=4">
@endpush

@push('scripts')
    <script src="{{ asset('js/recent.js') }}?v=1"></script>
    <script src="{{ asset('js/diseases.js') }}?v=1"></script>
    <script src="{{ asset('js/cara.js') }}?v=3"></script>
@endpush

@section('content')
@include('partials.hero')

@include('partials.howto')
@include('partials.diseases', ['diseases' => $diseases])
@include('partials.recent', ['recent' => $recent, 'total' => $total])
@endsection