@extends('layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('page-style')
    <style>
        .sickw-details-container {
            padding: 15px;
            border: 1px solid grey;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .debit-amount {
            color: red;
            font-size: 18px;
            text-align: right;
        }

        .credit-amount {
            color: green;
            font-size: 18px;
            text-align: right;
        }
    </style>
@endsection
@section('breadcrumb')
    @include('layouts.includes.breadcrumb')
@endsection
@section('content')

    <div class="row">
        @php
            $profit_calculation_var = $order->sell_amount;
        @endphp
        {{-- <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <th class="text-center" colspan="3" style="font-weight: bold;">
                                {{$page_title}}
                                <a href="{{ route('admin.order.invoice.download', $order->id) }}" style="float: right"><i class='bx bx-download'></i> Download Invoice</a>
                            </th>
                            <tr>
                                <th class="th-width col-3">Buyer</th>
                                <td colspan="2">{{ $order->buyer ? $order->buyer->company_name : '-'  }}</td>
                            </tr>
                            <tr>
                                <th class="th-width col-3">Pay Worker</th>
                                <td colspan="2">{{ $order->pay_worker ? $order->pay_worker->name : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="th-width col-3">Sell Amount</th>
                                <td>${{ number_format($order->sell_amount, 2) }}</td>
                                <td class="credit-amount">+ ${{ number_format($order->sell_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="th-width col-3">Payment From Buyer</th>
                                <td style="width:500px">
                                    @if ($order->payment_mode->isNotEmpty())
                                        <ul>
                                            @forelse ($order->payment_mode as $key => $payment_mode)
                                                <li>{{ $payment_mode->payment_name }} - ${{ number_format($payment_mode->pivot->amount, 2) }}</li>
                                            @empty

                                            @endforelse
                                        </ul>
                                    @else
                                        @if ($order->order_status == 'in-progress')
                                            <span class="badge rounded-pill badge-soft-danger font-size-15">Pending</span>
                                        @else
                                            -
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @foreach ($order->payment_mode as $key => $payment_mode)
                                        @if ($payment_mode->payment_name === 'Gift Card')
                                            Conversion of gift card to dollar (3%) :
                                            <span class="debit-amount">- ${{ (number_format((double)$payment_mode->pivot->amount * 0.03, 2)) }}</span>
                                            @php
                                                $profit_calculation_var -= ((double)$payment_mode->pivot->amount * 0.03);
                                            @endphp
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="th-width col-3">Pay Worker Payment</th>
                                <td>${{ number_format($order->pay_worker_payment, 2) }}</td>
                                <td class="debit-amount">- ${{ number_format($order->pay_worker_payment, 2) }}</td>
                                @php
                                    $profit_calculation_var -= $order->pay_worker_payment;
                                @endphp
                            </tr>
                            <tr>
                                <th class="th-width col-3">Order Status</th>
                                <td colspan="2">
                                    @if ($order->order_status == 'in-progress')
                                        <span class="badge rounded-pill badge-soft-warning font-size-15">In Progress</span>
                                    @elseif($order->order_status == 'complete')
                                        <span class="badge rounded-pill badge-soft-success font-size-15">Completed</span>
                                    @elseif($order->order_status == 'return')
                                        <span class="badge rounded-pill badge-soft-danger font-size-15">Returned</span><br /><br />
                                        <h6>Reason: </h6>
                                        <ul>
                                            <li>{{ $order->return_reason }}</li>
                                        </ul>
                                    @elseif($order->order_status == 'cancel')
                                        <span class="badge rounded-pill badge-soft-danger font-size-15">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <table class="table table-striped">
                            <th class="text-center" colspan="3" style="font-weight: bold;">Order Items</th>
                            @foreach ($order->inventory_items as $key => $inventory_items)
                            <tr>
                                <th class="th-width col-3">{{$inventory_items->phone_model->model_name}}</th>
                                <td>
                                    <h6>Expense</h6>
                                    <ul>
                                        @foreach ($inventory_items->phone_damages as $key => $phone_damage)
                                            <li>{{ $phone_damage->damage_name }} - ${{ number_format($phone_damage->pivot->expense_amount, 2) }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="debit-amount">
                                    @php
                                        $profit_calculation_var -= $inventory_items->purchase_price;
                                    @endphp
                                    - ${{$inventory_items->purchase_price}}<br />
                                    @foreach ($inventory_items->phone_damages as $key => $phone_damage)
                                        - ${{ number_format($phone_damage->pivot->expense_amount, 2) }}<br />
                                        @php
                                            $profit_calculation_var -= $phone_damage->pivot->expense_amount;
                                        @endphp
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </table>

                        <table class="table table-striped">
                            <td class="{{ ($profit_calculation_var > 0) ? 'credit-amount' : 'debit-amount' }}">Profit: {{ ($profit_calculation_var > 0) ? '+' : '-' }} ${{ number_format($profit_calculation_var, 2) }}</td>
                        </table>

                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice-title">
                        <h4 class="float-end font-size-16">Invoice # {{ $order->id }}</h4>
                        <div class="mb-4">
                            <img src="{{ asset('assets/images/fo-mart-logo.png') }}" width="150" alt="" />
                            {{-- <img src="assets/images/logo-dark.png" alt="logo" height="20"/> --}}
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <address>
                                <strong>From:</strong><br>
                                Fo Mart Pvt Ltd<br>
                            </address>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <address class="mt-2 mt-sm-0">
                                <strong> To:</strong><br>
                                {{ $order->buyer ? $order->buyer->company_name : '-' }}<br>
                                {{ $order->buyer ? $order->buyer->company_number : '-' }}<br>
                                {{ $order->buyer ? $order->buyer->address : '-' }}<br>
                            </address>
                        </div>
                    </div>
                    @php
                        $n = 1;
                        $item_amount = 0;
                        $gift_card_text = '';
                        $gift_card_amount = 0;
                        // ++$n;
                    @endphp
                    <div class="row">
                        <div class="col-sm-6 mt-3">
                            <address>
                                <strong>Payment Method:</strong><br>
                                @forelse($order->payment_mode as $key => $value)
                                    @if ($value->payment_name === 'Gift Card')
                                        {{ $value->payment_name }} : ${{ number_format($value->pivot->amount, 2) }}<br>
                                        @php
                                            $gift_card_text = 'Conversion of gift card to dollar (3%)';
                                        @endphp
                                        @php
                                            $gift_card_calculation = (float) $value->pivot->amount * 0.03;
                                            // $item_amount -= $gift_card_calculation;
                                            $gift_card_amount = $gift_card_calculation;
                                        @endphp
                                    @else
                                        {{ $value->payment_name }} : ${{ number_format($value->pivot->amount, 2) }}<br>
                                    @endif
                                @empty
                                    Pending
                                @endforelse
                            </address>
                        </div>
                        <div class="col-sm-6 mt-3 text-sm-end">
                            <address>
                                <strong>Order Date:</strong><br>
                                {{ $order->created_at->format('d/m/Y') }}<br>
                            </address>
                        </div>
                    </div>
                    <div class="py-2 mt-3">
                        <h3 class="font-size-15 font-weight-bold">Order Items</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">No.</th>
                                    <th>Item</th>
                                    <th>Item IMEI</th>
                                    <th>Purchase Price</th>
                                    <th class="text-end">Sellnig Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->inventory_items as $key => $inventory_items)
                                    <tr>
                                        <td>{{ $n++ }}</td>
                                        <td>{{ isset($inventory_items->phone_model) && $inventory_items->phone_model->model_name != null ? $inventory_items->phone_model->model_name : '-' }}
                                        </td>
                                        <td>{{ $inventory_items->imei_number }}</td>
                                        <td> ${{ number_format($inventory_items->purchase_price, 2) }}</td>
                                        <td class="text-end">${{ number_format($inventory_items->pivot->amount, 2) }}
                                        </td>
                                    </tr>
                                    @php
                                        $item_amount += $inventory_items->pivot->amount;
                                    @endphp
                                @endforeach
                                @php
                                    $e = 1;
                                @endphp

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2" class="text-end">Sub Total</td>
                                    <td class="text-end"> ${{ number_format($item_amount, 2) }}</td>
                                </tr>
                                @foreach ($order->expenses as $key => $expenses)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-end">{{ $expenses->expense_name }} <span
                                                style="font-size: 12px">(expenses)</span></td>
                                        <td class="text-end">- ${{ number_format($expenses->pivot->amount, 2) }}</td>
                                    </tr>
                                    @php
                                        $item_amount -= $expenses->pivot->amount;
                                    @endphp
                                @endforeach
                                @if ($gift_card_text != '')
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-end">{!! $gift_card_text !!} </td>
                                        <td class="text-end">- ${{ number_format($gift_card_amount, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2" class="text-end">Profit Amount</td>
                                    <td class="text-end">
                                        ${{ number_format((int) $item_amount - (int) $gift_card_amount - (int) $inventory_items->purchase_price, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-print-none">
                        <div class="float-end">
                            <a href="{{ route('admin.order.invoice.download', $order->id) }}"
                                class="btn btn-primary w-md waves-effect waves-light"><i class="fa fa-print"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
