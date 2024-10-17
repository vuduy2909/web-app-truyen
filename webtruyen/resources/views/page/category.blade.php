@extends('layout.front_page.master')
@section('main')
    @push('css')
        <link rel="stylesheet" href="{{ asset('front_asset/css/own/index.css') }}">
        <style>
            .cate_name,
            .cate_descriptions {
                color: #333;
            }
            body.dark .cate_name,
            body.dark .cate_descriptions{
                color: #ccc;
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render("show_categories", $category) }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2 class="cate_name">{{ $category->name }}</h2>
            <div class="cate_descriptions border my-2 mb-4 p-3" >
                {{ $category->descriptions }}
            </div>
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
