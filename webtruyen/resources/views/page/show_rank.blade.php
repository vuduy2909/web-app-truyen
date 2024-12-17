@extends('layout.front_page.master')
@section('main')
    @push('css')
        <link rel="stylesheet" href="{{ asset('front_asset/css/own/index.css') }}">
    @endpush
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render("show_rank") }}
        </div>
    </div>
    <div class="row">
        @foreach($data as $story)
            <div class="col-lg-2 col-sm-3 col-6">
                <x-story :story="$story"/>
            </div>
        @endforeach
    </div>
@endsection
