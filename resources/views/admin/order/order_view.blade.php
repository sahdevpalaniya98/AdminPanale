@extends('layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('page-style')

@endsection
@section('breadcrumb')
    @include('layouts.includes.breadcrumb')
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            {{-- @dd($order_item->order->sell_amount) --}}
            <h3>Selling Amount :
                <span class="text text-success">
                    +{{ isset($order) && $order->sell_amount != '' ? $order->sell_amount : '' }}
                </span>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                @if (isset($order) && !empty($order->inventory_items))
                    @foreach ($order->inventory_items as $key => $item)
                        <div class="col-md-6">
                            <h4 class="">{{ $item->phone_name }}</h4>
                            <ul>
                                <li>
                                    <p>Purchase Price :
                                        <span class="text text-danger h6">-{{ $item->purchase_price }}</span>
                                    </p>
                                </li>
                                <li>
                                    <p class="h6">Damages</p>
                                </li>
                                <ul>
                                    @foreach ($order->phone_damages as $key => $damage)
                                        <li>
                                            <p class="">{{ $damage->damage_name }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </ul>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Pay Worker</h3>
            <p><b>Worker Name :</b>
                <span class="h5">
                    {{ isset($order) && $order->pay_worker != '' ? $order->pay_worker->name : '' }}
                </span>
            </p>
            <p><b>Worker Payment :
                    <span class="text text-danger">
                        -{{ isset($order) && $order->pay_workder_payment != '' ? $order->pay_workder_payment : '' }}
                    </span>
                </b>
            </p>
        </div>
    </div>
    {{-- Count Profit  Start --}}
    @php
        $damage_amount = 0;
        $purchase_amount = 0;
        $pay_worker_amount = isset($order) && $order->pay_workder_payment != '' ? $order->pay_workder_payment : 0;
        foreach ($order->inventory_items as $key => $purches_item) {
            $purchase_amount = $purchase_amount + $purches_item->purchase_price;
        }
        foreach ($order->phone_damages as $key => $damage) {
            $damage_amount = $damage_amount + $damage->pivot->expense_amount;
        }
        $total_expence = $damage_amount + $purchase_amount + $pay_worker_amount;
        $seeling_amount = isset($order) && $order->sell_amount != '' ? $order->sell_amount : 0;
        $profit_amount = $seeling_amount - $total_expence;
    @endphp
    {{-- Count Profit  End --}}
    <div class="card">
        <div class="card-header">
            <h3>Profit :
                @if ($profit_amount > 0)
                    <span class="text text-success">
                        +{{ isset($profit_amount) && $profit_amount != '' ? $profit_amount : 0 }}
                    </span>
                @else
                    <span class="text text-danger">
                        {{ isset($profit_amount) && $profit_amount != '' ? $profit_amount : 0 }}
                    </span>
                @endif
            </h3>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
