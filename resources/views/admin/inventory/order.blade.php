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
                    <form class="form_repeater" action="{{ route('admin.inventory.order.store') }}" name="addfrm" id="addfrm"
                        method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="buyer_id">Buyer Name<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="buyer_id" id="buyer_id">
                                        <option value="">Please select Buyer</option>
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
                                        <option value="">Please select pay worker</option>
                                    </select>
                                    @error('pay_worker_id')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <input type="hidden" name="sell_amount" id="sell_amount">
                        </div>

                        {{-- Invetory Items End --}}
                        <div class='card-header mb-3 text-center'>
                            <h5><b>Invoice Items</b></h5>
                        </div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class="repeater">
                                    <div class="col-12" data-repeater-list="inventorys">
                                        <div data-repeater-item class="row mb-3" style="display:flex;align-items: end;">
                                            <div class="col-5 select_device">
                                                <label class="form-label" for="select-device">Select Device<span
                                                        class="text text-danger h5"> *</span></label>
                                                <select readonly class="form-select" name="inventory_id" id="inventory_id">
                                                    @if (isset($inventory_data->phone_model) && $inventory_data->phone_model != '')
                                                        <option value="{{ $inventory_data->id }}">
                                                            {{ $inventory_data->phone_model->model_name }}</option>
                                                    @endif
                                                </select>
                                                @error('inventory_id')
                                                    <span class="text text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-5">
                                                <label class="form-label" for="inventory-amount">Amount<span
                                                        class="text text-danger h5"> *</span></label>
                                                <input required type="text"
                                                    class="form-control input-mask    item_amount calculate_amount text-start"
                                                    name="per_amount"
                                                    data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'" />
                                                @error('per_amount')
                                                    <span class="text text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="view_invetory btn btn-outline-primary">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button data-repeater-delete type="button"
                                                    class="view_invetory btn btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="col-12 phone-details"></div>
                                        </div>
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
                                        <div data-repeater-item class="row mb-3" style="display:flex;align-items: end;">
                                            <div class="col-5">
                                                <label class="form-label" for="select-damage">Expense</label>
                                                <select class="form-select damage_id" name="damage_id">
                                                </select>
                                                @error('damage_id')
                                                    <span class="text text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                                <input type="hidden" name="expense_name" class="expense_name" />
                                            </div>
                                            <div class="col-5">
                                                <label class="form-label" for="damage-amount">Expense Amount</label>
                                                <input type="text"
                                                    class="form-control input-mask damage_amount calculate_amount inventory-items-expense-amt text-start"
                                                    name="damage_amount"
                                                    data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'" />
                                                @error('damage_amount')
                                                    <span class="text text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-2">
                                                <button data-repeater-delete type="button"
                                                    class="view_invetory btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <input data-repeater-create type="button" value="Add"
                                            class="btn btn-success mt-3 mt-lg-0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Invetory Damages End --}}

                        <div class='card-header mb-3 text-right' style="text-align: right;">
                            <h5><b>Items: </b><span id="item_total_txt">$0.00</span></h5>
                            <h5><b>Expense: </b><span id="item_total_expense_txt">$0.00</span></h5>
                            <h5><b>Total: </b><span id="total_amt_txt">$0.00</span></h5>
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
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
@section('scripts')
    @parent
    <script src="https://themesbrand.com/skote/layouts/assets/libs/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    <script src="https://themesbrand.com/skote/layouts/assets/libs/jquery.repeater/jquery.repeater.min.js"></script>

    <script type="text/javascript">
        function all_items_total() {
            var item_total_txt = 0;
            var expence_total_txt = 0;
            var pay_worker_payment = 0;
            $(".item_amount").each(function() {
                if ($(this).val() !== '') {
                    item_total_txt += parseFloat($(this).val().replace('$ ', ''));
                }
            });

            $(".damage_amount").each(function() {
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

        function inventory_html_render($data, renderDom) {
            const base_url_image = "{{ asset('uploads/brands/') }}";
            let html_expense = '';
            let html_phone_notes = '';
            let html_payment_mode = '';
            let total_expense_amount = 0;
            let total_amount = $data.purchase_price;
            if ($data.payment_modes.length > 0) {
                $data.payment_modes.map(($payment_modes) => {
                    html_payment_mode += `<p class="text-muted" style="margin-bottom: 0px;">
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
                        `<p class="text-muted" style="margin-bottom: 0px;"><i class="bx bx-chevrons-right font-size-16 align-middle text-primary me-1"></i> ${ $phone_damage.damage_name }</p>`;
                });
            }
            const html = `<div class="card mt-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="product-detai-imgs">
                                <div class="row">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="product-1-tab">
                                            <div>
                                                <img src="${base_url_image}/${($data.phone_brand != null) ? $data.phone_brand.brand_icon : 'default.jpg'}" alt="" class="img-fluid mx-auto d-block">
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
            $(this).parent('div').parent('div').find('.phone-details').toggle();
        });

        // $('.inventory_id').on('select2:select', function(e) {
        //     const phone_details_html = $(this).parent('div').parent('div').find('.phone-details');
        //     inventory_id = e.params.data.id;
        //     get_damage_list(inventory_id);
        //     inventory_html_render(e.params.data.all_data, phone_details_html);
        // });

        // $('.inventory_id').on('select2:unselect', function(e) {
        //     inventory_id = e.params.data.id;
        //     $('#device_damage_' + inventory_id).remove();
        //     calculation_items_amt();
        // });

        $(document).on('keyup', '.inventory-items-expense-amt', function() {
            // calculation_items_amt();
        });

        function calculation_items_amt() {
            const inputs = $('.inventory-items-amt');
            let total_input = 0;
            for (let index = 0; index < inputs.length; index++) {
                total_input += parseFloat($(inputs[index]).val().replace('$ ', ''));
            }
            $('#item_total_txt').html(`$${total_input.toFixed(2)}`);
            const expense_inputs = $('.inventory-items-expense-amt');
            let total_expense_input = 0;
            for (let index = 0; index < expense_inputs.length; index++) {
                if ($(expense_inputs[index]).val() !== '') {
                    total_expense_input += parseFloat($(expense_inputs[index]).val().replace('$ ', ''));
                }
            }
            $('#item_total_expense_txt').html(`$${total_expense_input.toFixed(2)}`);
            $('#total_amt_txt').html(`$${(total_input - total_expense_input).toFixed(2)}`);
            $('#sell_amount').val((total_input - total_expense_input));
        }

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
                    $(".input-mask").attr('data-inputmask',
                        "'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'"
                    );
                    $(".input-mask").inputmask();
                    $('.select-box').select2();
                    // calculation_items_amt();
                }
            });
        }

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
                    // alert('You have already selected!!');
                    $(this).val('').change();
                    return false;
                } else {
                    const phone_details_html = $(this).parent('div').parent('div').find('.phone-details');
                    inventory_html_render(e.params.data.all_data, phone_details_html);
                    $(this).parent('div').parent('div').find('.phone-details').hide();
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
            $("#mySelect2").select2("val", "0");
            $('.repeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                    $('.repeater').find('.select2-container').remove();
                    init_inventory_select();
                    init_damage_select();
                    $(".input-mask").inputmask();
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
