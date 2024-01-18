<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Booking Receipt</title>
	<style>
		.clearfix:after {
			content: "";
			display: table;
			clear: both;
		}

		html {
			zoom: 0.75;
		}

		body {
			height: 100vh;
			/* width: 20cm; */
			margin: 0 auto;
			font-size: 16px;
			line-height: 1.5;
			font-family: 'Roboto', sans-serif;
		}

		header {
			padding: 10px 0;
		}

		.invoice-header {
			display: flex;
			align-items: flex-start;
		}

		.invoice-header-section {
			/* width: 100%; */
			/* display: inline-block; */
		}

		.section {
			display: block;
			width: 100%;
		}

		.inner-section {
			width: 49%;
			display: inline-block;
		}

		#logo {
			text-align: left;
			margin-bottom: 10px;
		}

		#logo img {
			width: 90px;
		}

		h1 {
			border-top: 1px solid #dddddd;
			border-bottom: 1px solid #dddddd;
			color: #5D6975;
			line-height: 1.6em;
			font-weight: normal;
			text-align: center;
			font-size: 20px;
		}

		.text-muted {
			color: #5D6975;
		}

		.currency {
			font-size: 12px;
		}

		.text-right {
			text-align: right;
		}

		.separator {
			height: 1px;
			background-color: #dddddd;
			margin: 15px auto;
		}

		.pb-10 {
			padding-bottom: 10px;
		}

		.font-12 {
			font-size: 12px;
		}

		.booking-info {
			padding-bottom: 30px;
		}

		.booking-info .bi-text-left {
			float: left;
			width: 50%;
		}

		.booking-info .bi-text-right {
			float: right;
			text-align: right;
			width: 50%;
			text-transform: uppercase;
		}

		#departure {
			float: left;
			width: 45%;
		}

		#arrival {
			text-align: right;
			width: 45%;
			display: inline-block;
		}

		#duration {
			text-align: center;
			width: 9%;
			display: inline-block;
		}

		#departure div,
		#arrival div,
		#duration div {
			white-space: nowrap;
		}

		#checkin {
			float: left;
			width: 33%;
		}

		#checkout {
			text-align: center;
			width: 33%;
			display: inline-block;
			vertical-align: top;
		}

		#guest {
			text-align: right;
			width: 33%;
			display: inline-block;
		}

		#checkin div,
		#checkout div,
		#guest div {
			white-space: nowrap;
		}

		#visa-type {
			float: left;
			width: 33%;
		}

		#visa-issue-date {
			text-align: center;
			width: 33%;
			display: inline-block;
			vertical-align: top;
		}

		#visa-expiration-date {
			text-align: right;
			width: 33%;
			display: inline-block;
		}

		#visa-type div,
		#visa-issue-date div,
		#visa-expiration-date div {
			white-space: nowrap;
		}

		.hotel-info {
			padding-bottom: 15px;
		}

		.travellers ul {
			list-style-type: none;
		}

		.travellers li {
			padding-bottom: 5px;
		}

		.travellers .contact-info {
			padding-left: 40px;
		}

		.payment-breakdown .total-due {
			border-top: 1px solid #dddddd;
			padding-top: 15px;
		}

        table {
            width: 100%;
        }
        .table, th, td {
            border: 1px solid #AAB7B8;
            /* border-bottom: 1px solid #AAB7B8; */
            border-collapse: collapse;
            padding: 5px;
        }

        .date-and-pay{
            border: none;
        }
	</style>
</head>

<body>
	<header class="clearfix">
		{{-- <div id="logo">
			<img src="{{ public_path('assets/images/fo-mart-logo.png') }}" alt="" class="rounded-circle"/>
		</div> --}}
		<div class="invoice-header">
			<div class="invoice-header-section" style="float:left;">
                <div id="logo">
                    <img src="{{ public_path('assets/images/fo-mart-logo.png') }}" alt="" class="rounded-circle"/>
                </div>
            </div>
			<div class="invoice-header-section" style="float:right;"><b>Invoice #{{$order->id}} </b></div>
      <div style="clear: both;"></div>
		</div>
		<hr>
		<div class="section" style="align-items: baseline;display:flex;">
			<div class="inner-section">
				<div style="font-size: 18px;"><b>From,</b></div>
				<div>Fo Mart</div>
			</div>

			<div class="inner-section" style=" margin-top:50px; text-align: right;">
				<div style="font-size: 1.3em;"><b>To,</b></div>
				<div>{{ $order->buyer->company_name  }} </div>
				<div>{{ $order->buyer->company_number  }} </div>
				<div>{{ $order->buyer->address  }}</div>
			</div>
		</div>
	</header>
	{{-- <main>
		<div class="visa-contact-details">
			<h3>Order Items</h3>
			@foreach ($order->inventory_items as $key => $inventory_items)
					<div><strong>Items Name: </strong> {{$inventory_items->phone_model->model_name}}</div>
			@endforeach
			<div class="separator"></div>
			<div style="float: right;"><strong>Selling Amount: </strong> ${{ number_format($order->sell_amount, 2)}} </div>
		</div>
	</main> --}}

    <div class="invoice-header">
        <div class="invoice-header-section" style="float:left">
            <b>Payment Method </b>
            <br>
            @foreach($order->payment_mode as $key => $value)
                </b> {{ $value->payment_name }}: ${{number_format($value->pivot->amount, 2)}}<br>
            @endforeach
        </div>
        <div class="invoice-header-section" style="text-align: right;float:right">
            <b>Order Date:</b>
            {{ $order->created_at->format('d/m/Y') }}
        </div>
        <div style="clear: both;"></div>
    </div>

    <br>
    @php
        $profit_calculation_var = $order->sell_amount;
    @endphp
    <table class="table">
        <thead>
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Item</th>
            <th scope="col">Price</th>
          </tr>
        </thead>
        <tbody>
            @php
                $n = 1;
								$item_amount = 0;
            @endphp
            @foreach($order->inventory_items as $key => $inventory_items)
                <tr>
                    <td scope="row" style="text-align:center">{{ $n++ }}</td>
                    <td>{{$inventory_items->phone_model->model_name}}</td>
                    <td class="text-end" style="text-align:right">${{number_format($inventory_items->pivot->amount, 2)}}</td>
                </tr>
								@php
									$item_amount += $inventory_items->pivot->amount;
								@endphp
            @endforeach
            <tr>
                <td colspan="2" style="text-align:right">Total</td>
                <td class="price" style="text-align:right"> ${{ number_format($item_amount, 2) }}</td>
            </tr>
        </tbody>
      </table>
</body>
</html>
