@extends('layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('page-style')
<style>
    .sickw-details-container{
        padding: 15px;
        border: 1px solid grey;
        margin-top: 10px;
        margin-bottom: 10px;
        border-radius: 10px;
    }

    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
    ul li{
        list-style: none;
    }
</style>
@endsection
@section('breadcrumb')
    @include('layouts.includes.breadcrumb')
@endsection
@section('content')

@include('admin.inventory.inventory_view')


@endsection

