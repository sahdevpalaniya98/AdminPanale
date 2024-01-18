{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <th class="text-center" colspan="2" style="font-weight: bold;">{{$page_title}}</th>
                        <tr>
                            <th class="th-width col-3">Customer </th>
                            <td>{{ $inventory->customer->name  }}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Brand</th>
                            <td>{{ $inventory->phone_brand ? $inventory->phone_brand->brand_name : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Phone Series</th>
                            <td>{{ $inventory->phone_serice ? $inventory->phone_serice->series_name : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Phone Model</th>
                            <td>{{ $inventory->phone_model ? $inventory->phone_model->model_name : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Phone SICKW Details</th>
                            <td style="width:500px">{!! $inventory->phone_sickw_details !!}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Purchase Price</th>
                            <td>${{ number_format($inventory->purchase_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Phone Notes</th>
                            <td>
                                <ul>
                                    @foreach ($inventory->phone_damages as $key => $phone_damage)
                                        <li>
                                            {{ $phone_damage->damage_name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Payment Mode</th>
                            <td>
                                <ul>
                                    @foreach ($inventory->payment_modes as $key => $payment_modes)
                                        <li>
                                            {{ $payment_modes->payment_name }} - ${{ number_format($payment_modes->pivot->amount, 2) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th class="th-width">Employee </th>
                            <td>{{ $inventory->employee ? $inventory->employee->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="th-width">Pay Worker</th>
                            <td>{{ $inventory->pay_worker ? $inventory->pay_worker->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="th-width col-3">Expenses</th>
                            <td>
                                <ul>
                                    @foreach ($inventory->expenses as $key => $expenses)
                                        <li>
                                            {{ $expenses->expense_name }} - ${{ number_format($expenses->pivot->amount, 2) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{--  --}}

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-7 offset-md-1 col-sm-9 col-8">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="product-1" role="tabpanel"
                                            aria-labelledby="product-1-tab">
                                            <div>
                                                <img src="{{ asset('uploads/brands/' . $inventory->phone_brand->brand_icon) }}"
                                                    alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <div class="flex" style="display: flex;gap:25px;margin-left: 14%;">
                                    <div class="">
                                        <a class="active">
                                            <h5 class="font-size-15">Brand</h5>
                                            <div class="product-color-item border rounded">
                                                <img src="{{ asset('uploads/brands/' . $inventory->phone_brand->brand_icon) }}"
                                                    alt="" class="avatar-md">
                                            </div>
                                            <p>{{ $inventory->phone_brand->brand_name }}</p>
                                        </a>
                                    </div>
                                    <div class="">
                                        <a class="active">
                                            <h5 class="font-size-15">Category</h5>
                                            <div class="product-color-item">
                                                @php
                                                    $category_icon = '';
                                                    $category_name = '';
                                                    if (isset($inventory->category)) {
                                                        $category_icon = $inventory->category->category_icon;
                                                        $category_name = $inventory->category->category_name;
                                                    }
                                                @endphp
                                                <img src="{{ asset('uploads/category/' . $category_icon) }}"
                                                    alt="category" class="avatar-md">
                                            </div>
                                            <p>{{ $category_name }}</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class=" mt-xl-3">
                            <h5 class="text-primary">
                                {{ $inventory->phone_brand ? $inventory->phone_brand->brand_name : '-' }}</h5>
                            <h4 class="mt-1 mb-4">
                                {{ $inventory->phone_model ? $inventory->phone_model->model_name : '-' }}
                                @if ($inventory->is_sold == 1)
                                    <span style="font-size: 11px;" class="badge rounded-pill bg-success">Sold</span>
                                @else
                                    <span style="font-size: 11px;" class="badge rounded-pill bg-danger">Not Sold</span>
                                @endif
                            </h4>
                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <p class="" style="font-size: 15px"><b>Price :</b>
                                        ${{ number_format($inventory->purchase_price, 2) }}
                                    </p>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-6">
                                    <div>
                                        <p class="mb-4" style="font-size: 15px"><b>Phone Brand :</b>
                                            {{ $inventory->phone_brand ? $inventory->phone_brand->brand_name : '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <p class="mb-4" style="font-size: 15px"><b>Phone Category :</b>
                                            {{ $inventory->category ? $inventory->category->category_name : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div>
                                        <p class="mb-4" style="font-size: 15px"><b>Phone Series :</b>
                                            {{ $inventory->phone_serice ? $inventory->phone_serice->series_name : '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <p class="mb-4" style="font-size: 15px"><b>Phone Model :</b>
                                            {{ $inventory->phone_model ? $inventory->phone_model->model_name : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <p class="mb-1" style="font-size: 15px"><b>Payment Mode :</b>
                                    <ul class="">
                                        @foreach ($inventory->payment_modes as $key => $payment_modes)
                                            <li class="text-muted">
                                                <i
                                                    class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                <b>{{ $payment_modes->payment_name }} -</b>
                                                ${{ number_format($payment_modes->pivot->amount, 2) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1" style="font-size: 15px"><b>Inventory Order :</b>
                                    <ul class="">
                                        <li class="text-muted">
                                            <i
                                                class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                            <b>Purchase Price -</b>
                                            <span class="text text-danger">${{ number_format($inventory->purchase_price, 2) }}</span>
                                        </li>
                                        @if (isset($inventory->inventory_items) && $inventory->inventory_items != '')
                                            @foreach ($inventory->inventory_items as $items)
                                                <li class="text-muted">
                                                    <i
                                                        class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                    <b>Selling Price -</b>
                                                    <span class="text text-success">${{ number_format($items->sell_amount, 2) }}</span>
                                                </li>
                                                <li class="text-muted">
                                                    <i
                                                        class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                    <b>Order Date: -</b>
                                                    <span class="">{{ $items->created_at->format('d/m/Y') }}<br></span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    </h5>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div>
                                        <p class="mb-1" style="font-size: 15px"><b>Phone Expenses :</b>
                                        <ul>
                                            @foreach ($inventory->phone_damages as $key => $phone_damage)
                                                <li class="text-muted"><i
                                                        class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                    {{ $phone_damage->damage_name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6">
                                    <p class="mb-1" style="font-size: 15px"><b>Phone Details :</b>
                                    <ul>
                                        @if (isset($inventory) && $inventory->imei_number != '')
                                            <li class="text-muted"><i
                                                    class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                IMEI Number :
                                                {{ $inventory->imei_number }}
                                            </li>
                                        @endif
                                        @if (isset($inventory) && $inventory->serial_number != '')
                                            <li class="text-muted"><i
                                                    class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                Serial Number :
                                                {{ $inventory->serial_number }}
                                            </li>
                                        @endif
                                        @if (isset($inventory) && $inventory->phone_grade != '')
                                            <li class="text-muted"><i
                                                    class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                Phone Grade :
                                                {{ $inventory->phone_grade->grade_name }}
                                            </li>
                                        @endif
                                        @if (isset($inventory) && $inventory->bettry_health != '')
                                            <li class="text-muted"><i
                                                    class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                Phone Bettry Health :
                                                {{ $inventory->bettry_health }}
                                            </li>
                                        @endif
                                        @if ($inventory->inventory_variant->isNotEmpty())
                                            @foreach ($inventory->inventory_variant as $key => $variant)
                                                <li class="text-muted"><i
                                                        class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                    {{ $variant->type . ' - ' . $variant->title }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                @if ($inventory->phone_sickw_details != '')
                                    <div class="col-xl-12 col-md-12">
                                        <p class="mb-1" style="font-size: 15px"><b>Specifications :</b></p>
                                        <div class="col-xl-12">
                                            <div class="">
                                                <p class="text-muted mb-4 more">{!! $inventory->phone_sickw_details !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Configure/customize these variables.
            var showChar = 109; // How many characters are shown by default
            var ellipsestext = "";
            var moretext = "Read More";
            var lesstext = "Read Less";

            $('.more').each(function() {
                var content = $(this).html();

                if (content.length > showChar) {
                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);
                    var html = c + '<span class="moreellipses">' + ellipsestext +
                        '&nbsp;</span><span class="morecontent"><span>' + h +
                        '</span>&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }
            });

            $(".morelink").click(function() {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }

                $(this).parent().prev().toggle();
                $(this).prev().toggle();

                return false;
            });
        });
    </script>
@endsection
