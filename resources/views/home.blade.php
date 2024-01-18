@extends('layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('page-style')
    <style>
        .media {
            display: flex;
            justify-content: space-between;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @php
        $total_selling_amount = 0;
        $total_purchase_amount = 0;
        $total_damage_amount = 0;
        foreach ($orders as $key => $order) {
            $total_selling_amount += $order->sell_amount;
            if ($order->inventory_items) {
                foreach ($order->inventory_items as $key => $inventory_item) {
                    // echo $inventory_item->purchase_price;
                    // echo "<br />";
                    $total_purchase_amount += $inventory_item->purchase_price;
                }
            }
            if ($order->phone_damages) {
                foreach ($order->phone_damages as $key => $damage) {
                    $total_damage_amount += $damage->pivot['amount'];
                }
            }
        }
        $profit = $total_selling_amount - $total_purchase_amount;
    @endphp
    <div class="row">

        {{-- Total Grand Sales --}}
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Grand Sales</p>
                            <h4 class="mb-0">{{ $total_selling_amount }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bx-dollar font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Grand Sales --}}

        {{-- Total Profit --}}
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Profit</p>
                            <h4 class="mb-0">{{ $profit }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bx-dollar font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Profit --}}

        {{-- Total Orders --}}
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Orders</p>
                            <h4 class="mb-0">{{ $total_order }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bx-shopping-bag font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Orders --}}

    </div>

    <div class="row">

        {{-- Total Complete Order --}}
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Complete Order</p>
                            <h4 class="mb-0">{{ $orders->count() }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bx-check-circle font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Complete Order --}}

        {{-- Total Progress Orders --}}
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Progress Order</p>
                            <h4 class="mb-0">{{ $in_progress }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bxs-hourglass font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Progress Orders --}}

         {{-- Total Cancel Orders --}}
         <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Cancel & Return Orders</p>
                            <h4 class="mb-0">{{ $cancel_order }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bx-x font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Cancel Orders --}}

    </div>

    <div class="row">

         {{-- Total Cutomer --}}
         <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Customer</p>
                            <h4 class="mb-0">{{ $total_customer }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bxs-user-rectangle font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Cutomer --}}

        {{-- Total Buyer --}}
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Buyer</p>
                            <h4 class="mb-0">{{ $total_buyers }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bxs-group font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Buyer --}}


         {{-- Total Buyer --}}
         <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Total Payworker</p>
                            <h4 class="mb-0">{{ $total_payworker }}</h4>
                        </div>

                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                            <span class="avatar-title">
                                <i class="bx bxs-user-detail font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Buyer --}}

    </div>
@endsection
@section('page-script')
@endsection
