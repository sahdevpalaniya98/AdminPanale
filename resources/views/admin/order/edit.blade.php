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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.order.store') }}" name="addfrm" id="addfrm" method="POST">
                        @csrf

                        @isset($order)
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @endisset

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="buyer_id">Buyer Name<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="buyer_id" id="buyer_id">
                                        <option selected value="{{ $order->buyer_id }}">{{ $order->buyer->company_name }}
                                        </option>
                                    </select>
                                    @error('buyer_id')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="pay_worker_id">Pay Worker Name<span
                                            class="text text-danger h5"> *</span></label>
                                    <select class="form-select select2" name="pay_worker_id" id="pay_worker">
                                        <option selected value="{{ $order->pay_worker_id }}">{{ $order->pay_worker->name }}
                                        </option>
                                    </select>
                                    @error('pay_worker_id')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="sell_amount" id="sell_amount" value="{{ $order->sell_amount }}">

                        {{-- Invetory Items End --}}
                        <div class='card-header mb-3 text-center'>
                            <h5><b>Invoice Items</b></h5>
                        </div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class="repeater">
                                    <div class="col-12" data-repeater-list="inventorys">
                                        @foreach ($order->inventory_items as $key => $inventory_item)
                                            @php
                                                $expense_html = '<ul>';
                                                $total_amount = (int) $inventory_item->purchase_price;
                                                $total_expense_amount = 0;
                                                if ($inventory_item->expenses->isNotEmpty()) {
                                                    foreach ($inventory_item->expenses as $key => $expenses) {
                                                        $expense_html .= '<li>' . $expenses->expense_name . ' - ' . $expenses->pivot->amount . '</li>';
                                                        $total_amount += (int) $expenses->pivot->amount;
                                                        $total_expense_amount += (int) $expenses->pivot->amount;
                                                    }
                                                }
                                            @endphp
                                            @php
                                                $getAllvariant = [];
                                                foreach ($inventory_item->inventory_variant as $key => $variant) {
                                                    array_push($getAllvariant, $variant->title);
                                                }
                                                $getvariant = isset($getAllvariant) && $getAllvariant != '' ? ' ' . implode(', ', $getAllvariant) : null;
                                                $imei = isset($inventory_item->imei_number) && $inventory_item->imei_number != '' ? '(IMEI-' . $inventory_item->imei_number . ')' : null;
                                            @endphp
                                            <div data-repeater-item class="row mb-3" style="display:flex;align-items: end;">
                                                <div class="col-5 select_device">
                                                    <label class="form-label" for="select-device">Select Device<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <select class="form-select inventory_id" name="inventory_id" required>
                                                        <option selected value="{{ $inventory_item->id }}">
                                                            {{ $inventory_item->phone_model->model_name . $getvariant . $imei }}
                                                        </option>
                                                    </select>
                                                    @error('inventory_id')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-5">
                                                    <label class="form-label" for="pay-worker-payment">Amount<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <input type="text" class="form-control item_amount calculate_amount"
                                                        name="per_amount" placeholder="Selected device selling price"
                                                        required value="{{ $inventory_item->pivot->amount }}" />
                                                    @error('per_amount')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="view_invetory btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button data-repeater-delete type="button"
                                                        class="view_invetory btn btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="col-12 phone-details">
                                                    <div class="card mt-1">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-3">
                                                                    <div class="product-detai-imgs">
                                                                        <div class="row">
                                                                            <div class="tab-content"
                                                                                id="v-pills-tabContent">
                                                                                <div class="tab-pane fade show active"
                                                                                    id="" role="tabpanel"
                                                                                    aria-labelledby="product-1-tab">
                                                                                    <div>
                                                                                        <img src="{{ asset('uploads/brands/' . $inventory_item->phone_brand->brand_icon) }}"
                                                                                            alt=""
                                                                                            class="img-fluid mx-auto d-block">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-5">
                                                                    <div class="mt-4 mt-xl-3">
                                                                        <a href="javascript:void(0)"
                                                                            class="text-primary">{{ $inventory_item->phone_brand ? $inventory_item->phone_brand->brand_name : '-' }}</a>
                                                                        <h4 class="mt-1 mb-3">
                                                                            {{ ($inventory_item->phone_brand ? $inventory_item->phone_brand->brand_name : '-') . ' ' . ($inventory_item->phone_serice ? $inventory_item->phone_serice->series_name : '-') . ' - ' . ($inventory_item->phone_model ? $inventory_item->phone_model->model_name : '-') }}
                                                                        </h4>

                                                                        <p class="text-muted mb-4 more"
                                                                            style="max-height: 125px;overflow:auto;">
                                                                            {!! $inventory_item->phone_sickw_details !!}</p>

                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-4">
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <b> Phone Notes </b><br>
                                                                            <div>
                                                                                @foreach ($inventory_item->phone_damages as $key => $phone_damage)
                                                                                    <p class="text-muted"><i
                                                                                            class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                                                        {{ $phone_damage->damage_name }}
                                                                                    </p>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <b>Payment Mode</b>
                                                                            <div>
                                                                                @foreach ($inventory_item->payment_modes as $key => $payment_modes)
                                                                                    <p class="text-muted">
                                                                                        <i
                                                                                            class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                                                                                        {{ $payment_modes->payment_name }}
                                                                                        -
                                                                                        ${{ number_format($payment_modes->pivot->amount, 2) }}
                                                                                    </p>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <b>Expenses</b>
                                                                    <div class="mb-4">
                                                                        @foreach ($inventory_item->expenses as $key => $expenses)
                                                                            <p style="margin-bottom: 0px;"><i
                                                                                    class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>{{ $expenses->expense_name }}
                                                                                -
                                                                                ${{ number_format($expenses->pivot->amount, 2) }}
                                                                            </p>
                                                                        @endforeach
                                                                    </div>
                                                                    <div style="display:flex;">
                                                                        <h5>Price :
                                                                            <b>${{ number_format($inventory_item->purchase_price, 2) }}</b>
                                                                        </h5>
                                                                        <h5>Expense :
                                                                            <b>${{ number_format($total_expense_amount, 2) }}</b>
                                                                        </h5>
                                                                        <h5>Total :
                                                                            <b>${{ number_format($total_amount, 2) }}</b>
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-12">
                                        <input data-repeater-create type="button" value="Add"
                                            class="btn btn-outline-success mt-3 mt-lg-0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Invetory Items End --}}


                        {{-- Invetory Damages --}}
                        <div class='card-header mb-3 text-center'>
                            <h5><b>Expenses</b></h5>
                        </div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class="repeater">
                                    <div class="col-12" data-repeater-list="damages">
                                        @foreach ($order->phone_damages as $key => $order_damages)
                                            <div data-repeater-item class="row mb-3"
                                                style="display:flex;align-items: end;">
                                                <div class="col-5">
                                                    <label class="form-label" for="select-damage">Expense<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <select required class="form-select damage_id" name="damage_id">
                                                        <option selected value="{{ $order_damages->id }}">
                                                            {{ $order_damages->expense_name }}
                                                        </option>
                                                    </select>
                                                    <input type="hidden" name="expense_name" class="expense_name"
                                                        value="{{ $order_damages->expense_name }}" />
                                                    @error('damage_id')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-5">
                                                    <label class="form-label" for="damage-amount">Expense Amount<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <input type="text"
                                                        class="form-control damage_amount calculate_amount"
                                                        name="damage_amount" required
                                                        value="{{ $order_damages->pivot->amount }}"
                                                        placeholder="Selected device damage price" />
                                                    @error('damage_amount')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-2">
                                                    <button data-repeater-delete type="button"
                                                        class="btn btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-12">
                                        <input data-repeater-create type="button" value="Add"
                                            class="btn btn-outline-success mt-3 mt-lg-0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Invetory Damages End --}}

                        <div id="addInventory"></div>

                        <div class='card-header mb-3 text-right' style="text-align: right;">
                            <h5><b>Items: </b><span id="item_total_txt">$0.00</span></h5>
                            <h5><b>Expense: </b><span id="item_total_expense_txt">$0.00</span></h5>
                            <h5><b>Total: </b><span id="total_amt_txt">$0.00</span></h5>
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Update
                            </button>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-secondary waves-effect">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Modal -->
<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style="margin-top: 0%">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 721px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Device Details</h5>
                <button type="button" class="modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
@section('scripts')
    @parent
    <script src="https://themesbrand.com/skote/layouts/assets/libs/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    <script src="https://themesbrand.com/skote/layouts/assets/libs/jquery.repeater/jquery.repeater.min.js"></script>

    <script type="text/javascript">
        function all_items_total() {
            var item_total_txt = 0;
            var expence_total_txt = 0;
            var pay_worker_payment = 0;
            // if ($("#pay_worker_payment").val() !== '') {
            //     pay_worker_payment = parseFloat($("#pay_worker_payment").val().replace('$ ', ''));
            // }
            $(".item_amount").each(function() {
                if ($(this).val() !== '') {
                    item_total_txt += parseFloat($(this).val().replace('$ ', ''));
                }
            });

            $(".damage_amount").each(function() {
                console.log('here');
                if ($(this).val() !== '') {
                    expence_total_txt += parseFloat($(this).val().replace('$ ', ''));
                }
            });
            expence_total_txt += parseInt(pay_worker_payment);
            var total_amt = parseInt(item_total_txt) - parseInt(expence_total_txt);
            if (!isNaN(expence_total_txt)) {
                $('#item_total_expense_txt').html(`$${expence_total_txt.toFixed(2)}`);
            }
            if (!isNaN(item_total_txt)) {
                $('#item_total_txt').html(`$${item_total_txt.toFixed(2)}`);
            }
            if (!isNaN(total_amt)) {
                $('#total_amt_txt').html(`$${(total_amt).toFixed(2)}`);
            }
            $('#sell_amount').val(total_amt);
        }

        $(document).on('keyup', '.calculate_amount', function() {
            all_items_total();
        });

        $(document).ready(function() {
            all_items_total();
            init_inventory_select();
            init_damage_select();
            $(".input-mask").inputmask();
            $("#addfrm").validate({
                ignore: [],
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    if (element.hasClass('dropify')) {
                        error.insertAfter(element.closest('div'));
                    } else if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('span'));
                    } else if (element.attr("type") == "radio") {
                        $(element).parents('.radio-list').append(error)
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    buyer_id: {
                        required: true
                    },
                    pay_worker: {
                        required: true
                    },
                    pay_worker_payment: {
                        required: true
                    },
                    sell_amount: {
                        required: true
                    },
                },

                messages: {
                    buyer_id: {
                        required: "The Buyer name field is required."
                    },
                    pay_worker: {
                        required: "The pay worker field is required."
                    },
                    pay_worker_payment: {
                        required: "The pay worker payment field is required."
                    },
                    sell_amount: {
                        required: "The selling amount field is required."
                    },

                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });

        $('#buyer_id').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.buyer.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.text,
                                id: item.id,
                                slug: item.slug
                            }
                        })
                    };
                }
            }
        });

        $('#pay_worker').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.pay_worker.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.text,
                                id: item.id,
                                slug: item.slug
                            }
                        })
                    };
                }
            }
        });

        let inventory_id = null;

        function get_damage_list(inventory_id) {
            $.ajax({
                type: "post",
                url: "{{ route('admin.ajax.damage.list') }}",
                data: {
                    inventory_id: inventory_id
                },
                dataType: "html",
                success: function(response) {
                    $('#addInventory').append(response);
                    $('.select-box').select2();
                    $(".input-mask").attr('data-inputmask',
                        "'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'"
                    );
                    $(".input-mask").inputmask();
                }
            });
        }

        function inventory_html_render($data, renderDom) {
            const base_url_image = "{{ asset('uploads/brands/') }}";
            let html_expense = '';
            let html_phone_notes = '';
            let html_payment_mode = '';
            let total_expense_amount = 0;
            let total_amount = $data.purchase_price;
            if ($data.payment_modes.length > 0) {
                $data.payment_modes.map(($payment_modes) => {
                    html_payment_mode += `<p class="text-muted">
                        <i class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>
                        ${ $payment_modes.payment_name } - $${ parseFloat($payment_modes.pivot.amount).toFixed(2) }
                    </p>`;
                });
            }
            if ($data.expenses.length > 0) {
                $data.expenses.map(($expenses) => {
                    html_expense +=
                        `<p style="margin-bottom: 0px;"><i class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i>${ $expenses.expense_name } - $${parseFloat($expenses.pivot.amount).toFixed(2)}</p>`;
                    total_expense_amount += $expenses.pivot.amount;
                    total_amount += $expenses.pivot.amount;
                });
            }
            if ($data.phone_damages.length > 0) {
                $data.phone_damages.map(($phone_damage) => {
                    html_phone_notes +=
                        `<p class="text-muted"><i class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i> ${ $phone_damage.damage_name }</p>`;
                });
            }
            const html = `
            <div class="card mt-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="product-detai-imgs">
                                <div class="row">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="product-1-tab">
                                            <div>
                                                <img src="${base_url_image}/${$data.phone_brand.brand_icon}" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5">
                            <div class="mt-4 mt-xl-3">
                                <a href="javascript:void(0)" class="text-primary">${ $data.phone_brand ? $data.phone_brand.brand_name : '-' }</a>
                                <h4 class="mt-1 mb-3">
                                    ${($data.phone_brand ? $data.phone_brand.brand_name : '-')}
                                    ${($data.phone_serice ? $data.phone_serice.series_name : '-')} - 
                                    ${($data.phone_model ? $data.phone_model.model_name : '-')}
                                </h4>

                                <p class="text-muted mb-4 more" style="max-height: 125px;overflow:auto;">${$data.phone_sickw_details}</p>
                                
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <b> Phone Notes </b><br>
                                    <div>${html_phone_notes}</div>
                                </div>
                                <div class="col-md-6">
                                    <b>Payment Mode</b>
                                    <div>${html_payment_mode}</div>
                                </div>
                            </div>
                            <b>Expenses</b>
                            <div class="mb-4">${html_expense}</div>
                            <div style="display:flex;justify-content: space-around;">
                                <h5>Price : <br /><b>$${$data.purchase_price}</b></h5>
                                <h5>Expense : <br /><b>$${total_expense_amount}</b></h5>
                                <h5>Total : <br /><b>$${total_amount}</b></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            $(renderDom).html(html);
        }

        $(document).on('click', '.view_invetory', function() {
            $('.inventory_id').each(function() {
                if ($(this).is(':selected')) {
                    const phone_details_html = $(this).parent('div').parent('div').find('.phone-details');
                    inventory_html_render(e.params.data.all_data, phone_details_html);
                }
            });
            $(this).parent('div').parent('div').find('.phone-details').toggle();
        });

        function init_inventory_select() {
            $('.inventory_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.inventory.list') }}",
                    dataType: 'json',
                    minimumInputLength: 3,
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                        };
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.text,
                                    id: item.id,
                                    all_data: item.all_data
                                }
                            })
                        };
                    }
                }
            }).on('select2:select', function(e) {
                let isSelected = false;
                $('.inventory_id').not(this).each(function() {
                    if ($(this).val() == e.params.data.id) {
                        isSelected = true;
                    }
                });
                if (isSelected === true) {
                    Swal.fire({
                        title: 'Warning',
                        text: "You have already selected!!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'Ok',
                    });
                    $(this).val('').change();
                    return false;
                } else {
                    const phone_details_html = $(this).parent('div').parent('div').find('.phone-details');
                    inventory_html_render(e.params.data.all_data, phone_details_html);
                    $(this).parent('div').parent('div').find('.phone-details').hide();
                    return true;
                }
            });
        }

        function init_damage_select() {
            $('.damage_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.expense.list') }}",
                    dataType: 'json',
                    minimumInputLength: 3,
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                        };
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.text,
                                    id: item.id,
                                }
                            })
                        };
                    }
                }
            }).on('select2:select', function(e) {
                let isSelected = false;
                $('.damage_id').not(this).each(function() {
                    const inventory_id_selected = $(this).val();
                    if (inventory_id_selected == e.params.data.id) {
                        isSelected = true;
                    }
                });
                if (isSelected === true) {
                    Swal.fire({
                        title: 'Warning',
                        text: "You have already selected!!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'Ok',
                    });
                    $(this).val('').change();
                    return false;
                } else {
                    $(this).parent('div').parent('div').find('.expense_name').val(e.params.data.text);
                }
            });
        }

        $(document).ready(function() {
            $('.phone-details').each(function() {
                $(this).toggle();
            })
            $('.repeater').repeater({

                initEmpty: false,

                show: function() {
                    $(this).slideDown();
                    $('.repeater').find('.select2-container').remove();
                    $(this).find('.phone-details').html('');
                    init_inventory_select();
                    init_damage_select();
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function(setIndexes) {},

                isFirstItemUndeletable: true
            })
        });
    </script>

@endsection
