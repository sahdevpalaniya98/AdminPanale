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
    </style>
@endsection
@section('breadcrumb')
    @include('layouts.includes.breadcrumb')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <form action="{{ route('admin.inventory.store') }}" name="addfrm" id="addfrm" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="outer-repeater">
                        <div class="col-12 outer" data-repeater-list="all_inventory">
                            <div class="card outer" data-repeater-item>
                                <div class='card-header mb-3 text-center'>
                                    <h5><b>Inventory</b></h5>
                                </div>
                                <div class="card-body">
                                    <div class="inventory-body">
                                        <div class="row payment_type_inputs">
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="brand_id">Brand<span
                                                            class="text text-danger h5">
                                                            *</span></label>
                                                    <select class="form-select brand_id form-validat" name="brand_id">
                                                    </select>
                                                    @error('brand_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="category_id">Phone Category<span
                                                            class="text text-danger h5">
                                                            *</span></label>
                                                    <select class="form-select select2 category_id form-validat"
                                                        name="category_id">
                                                    </select>
                                                    @error('category_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="series_id">Phone Series<span
                                                            class="text text-danger h5">
                                                            *</span></label>
                                                    <select class="form-select series_id form-validat" name="series_id">
                                                    </select>
                                                    @error('series_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="model_id">Phone Model<span
                                                            class="text text-danger h5">
                                                            *</span></label>
                                                    <select class="form-select model_id form-validat" name="model_id">
                                                    </select>
                                                    @error('model_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="variant_id">Phone Variant</label>
                                                    <select class="form-select variant_id" name="variant_id"
                                                        multiple="multiple">
                                                    </select>
                                                    @error('variant_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="customer">Customer<span
                                                            class="text text-danger h5">
                                                            *</span></label>
                                                    <select class="form-control customer form-validat" name="customer">
                                                    </select>
                                                    @error('customer')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="purchase_price">Purchase Price <span
                                                            class="text text-danger h5">*</span></label>
                                                    <input name="purchase_price" value=""
                                                        class="form-control input-mask text-start purchase_price form-validat class_payment_mode_amount"
                                                        data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'">
                                                    @error('purchase_price')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone_grade">Phone Grade</label>
                                                    <select style="width:100%;" class="form-select phone_grade"
                                                        name="phone_grade" placeholder="Please Select Phone Grade">
                                                    </select>
                                                    @error('phone_grade')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone_notes">Phone Notes<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <select style="width:100%;"
                                                        class="form-select phone_notes_type form-validat" name="phone_notes"
                                                        multiple="multiple" placeholder="Please Select Phone Notes">
                                                    </select>
                                                    @error('phone_notes')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employee_id">Employee<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <select class="form-control employee_id form-validat"
                                                        name="employee_id">
                                                    </select>
                                                    @error('employee_id')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="pay_worker_id">Pay Worker Name</label>
                                                    <select class="form-select select2 pay_worker" name="pay_worker_id">
                                                    </select>
                                                    @error('pay_worker_id')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="payment_mode">Payment Mode<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <select class="form-control payment_mode form-validat"
                                                        name="payment_mode" multiple="multiple">
                                                    </select>
                                                    @error('payment_mode')
                                                        <span class="text text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input readonly type="hidden" name="arrya_of_amount"
                                                value=""class="form-control arrya_of_amount" />
                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="serial_number">Serial Number<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <input type="text" name="serial_number" value=""
                                                        class="form-control input-mask text-start serial_number form-validat" />
                                                </div>
                                            </div>

                                            <div class="col-4 bettry_health_div d-none">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bettry_health">Bettry Health<span
                                                            class="text text-danger h5"> *</span></label>
                                                    <input type="text" class="form-control bettry_health"
                                                        name="bettry_health" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 errors-container"></div>
                                    </div>

                                    <div class='card-header mb-3 text-center'>
                                        <h5><b>Expenses</b></h5>
                                    </div>
                                    <div class='card-body'>
                                        <div class='row'>
                                            <div class="inner-repeater">
                                                <div data-repeater-list="expense" class="inner">
                                                    <div data-repeater-item class="row mb-3"
                                                        style="display:flex;align-items: end;">
                                                        <div class="col-5">
                                                            <label class="form-label" for="select-damage">Expense</label>
                                                            <select class="form-select damage_id" name="damage_id">
                                                                <option value=""></option>
                                                            </select>
                                                            @error('damage_id')
                                                                <span class="text text-danger" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                            <input type="hidden" name="expense_name"
                                                                class="expense_name" value="" />
                                                        </div>
                                                        <div class="col-5">
                                                            <label class="form-label" for="damage-amount">Expense
                                                                Amount</label>
                                                            <input type="text"
                                                                class="form-control input-mask damage_amount inventory-items-expense-amt text-start"
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
                                                                class="btn btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input data-repeater-create type="button" value="Expense Add"
                                                    class="btn btn-info inner" />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="row">
                                            <label class="form-label" for="select-damage">Phone IMEI Number <span class="text text-danger">*<span></label>
                                            <input type="text" class="form-control form-validat" name="imei_number"
                                                value="" maxlength="150" placeholder="Enter IMEI Number">
                                        </div>
                                        {{-- <button type="button" class="btn btn-primary get_sickw_button">Get
                                                Data</button> --}}
                                    </div>
                                    {{-- --------------------- Delete Button -------------------------- --}}
                                    <div class="col-2 mt-2">
                                        <button data-repeater-delete type="button" class="btn btn-danger">Inventory
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <input data-repeater-create type="button" value="Add Inventory"
                                class="btn btn-success mt-3 mt-lg-0 disbled_buttons" />
                            <button type="submit" id="add_inventory_submit_btn"
                                class="btn btn-primary waves-effect waves-light disbled_buttons">
                                Save
                            </button>
                            <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary waves-effect">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="https://themesbrand.com/skote/layouts/assets/libs/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    {{-- <script src="https://themesbrand.com/skote/layouts/assets/libs/jquery.repeater/jquery.repeater.min.js"></script> --}}
    <script src="{{ asset('js/jquery_repeater.js') }}"></script>
    <script>
        let SelectedBrand, SelectedCategory, SelectedSeries, SelectedModel = '';

        /* ---------------------------- Jquey Validation ---------------------------- */
        let form_submit_status = '';

        /* ----------------------------Form Validation Function---------------------------- */
        function formvalidation() {
            form_submit_status = true;
            $('.form-validat').each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    form_submit_status = false;
                    $(this).parent('div').find('.error').remove();
                    $(this).parent('div').append(
                        '<span class="error text text-danger">This field is required</span>');
                } else {
                    $(this).parent('div').find('.error').remove();
                }
            });
        }

        /* ----------------------------Form SUbmit Jquery---------------------------- */
        $(document).on('submit', '#addfrm', function(event) {
            formvalidation();
            if (form_submit_status) {
                $("addfrm").submit();
            } else {
                event.preventDefault();
            }
        });


        /* ------------------ On Select2 Change Depended Value null ----------------- */
        $(document).on('change', '.brand_id', function() {
            $(this).parent('div').parent('div').parent('div').find('.category_id').val(null).trigger('change');
        });
        $(document).on('change', '.category_id', function(e) {
            $(this).parent('div').parent('div').parent('div').find('.series_id').val(null).trigger('change');
        });
        $(document).on('change', '.series_id', function(e) {
            $(this).parent('div').parent('div').parent('div').find('.model_id').val(null).trigger('change');
        });
        $(document).on('change', '.model_id', function(e) {
            $(this).parent('div').parent('div').parent('div').find('.variant_id').val(null).trigger('change');
        });

        /* ------------ If Grade B and C selected that time show bettry helth field -------------- */
        $(document).on('change', '.phone_grade', function(e) {
            if ($(this).val() == 2 || $(this).val() == 3) {
                $(this).parent('div').parent('div').parent('div').find('.bettry_health_div')
                    .removeClass("d-none");
                $(this).parent('div').parent('div').parent('div').find('.bettry_health').addClass(
                    "form-validat");
            } else {
                $(this).parent('div').parent('div').parent('div').find('.bettry_health_div').addClass(
                    "d-none");
                $(this).parent('div').parent('div').parent('div').find('.bettry_health').removeClass(
                    'form-validat');
            }
        });

        $(document).on('select2:select', '.payment_mode', function(e) {
            var data = e.params.data;
            $(this).parent('div').parent('div').parent('.payment_type_inputs').append(`
                <div class="col-4 payment_input_index_${data.id}">
                    <div class="mb-3">
                        <label class="form-label" for="model_id">${data.text} Amount<span class="text text-danger h5"> *</span></label>
                        <input name="payment_mode_amount" class="form-control input-mask text-start class_payment_mode_amount payment_mode_amount_${data.id}" value="" placeholder="$ 0.00">
                    </div>
                </div>
            `);
        });
        /* ------------------------- Purchase price and paymend mode value equal ------------------------ */
        let payment_input_amounts_amount = 0;
        let arrya_of_amount = [];
        $(document).on("blur", '.class_payment_mode_amount', function() {
            let payment_mode_val = $(this).parent('div').parent('div').parent('div').find('.payment_mode').val();
            let purchase_amount = parseInt(Number($(this).parent('div').parent('div').parent('div').find(
                '.purchase_price').val().replace('$ ', '')));
            var total = 0;
            arrya_of_amount = [];
            for (let index = 0; index < payment_mode_val.length; index++) {
                total += parseInt(Number($(this).parent('div').parent('div').parent('div').find('.payment_mode_amount_'+ payment_mode_val[index]).val()));
                arrya_of_amount.push(parseInt(Number($(this).parent('div').parent('div').parent('div').find('.payment_mode_amount_'+ payment_mode_val[index]).val())));
                }
            payment_input_amounts_amount = total;

            $(this).parent('div').parent('div').parent('div').find('.arrya_of_amount').val(arrya_of_amount)
            if (payment_input_amounts_amount == purchase_amount) {
                $(".disbled_buttons").prop("disabled", false);
                $(this).parent('div').parent('div').parent('div').parent('div').find('.errors-container').html('');
            } else {
                $(this).parent('div').parent('div').parent('div').parent('div').find('.errors-container').html(
                    '<span class="text text-danger" role="alert">Partial amount should be equal to purchase amount.</span>'
                );
                $(".disbled_buttons").prop("disabled", true);
            }
        });

        /* ---------------------------- Brand Select call --------------------------- */
        function init_brand_select() {
            $('.brand_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.brand.list') }}",
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
            }).on('select2:select', function(e) {
                SelectedBrand = $(this).val();
            });
        }

        /* -------------------------- Category Select call -------------------------- */
        function init_category_select() {
            $('.category_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.category.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            brand_id: SelectedBrand,
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
            }).on('select2:select', function(e) {
                SelectedCategory = $(this).val();
            });
        }

        /* --------------------------- Series Select call --------------------------- */
        function init_series_select() {
            $('.series_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.series.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            category_id: SelectedCategory,
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
            }).on('select2:select', function(e) {
                SelectedSeries = $(this).val();
            });
        }

        /* --------------------------- Model Select2 call --------------------------- */
        function init_model_select() {
            $('.model_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.model.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            brand_id: SelectedBrand,
                            series_id: SelectedSeries,
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
            }).on('select2:select', function(e) {
                SelectedModel = $(this).val();
            });
        }

        /* --------------------------------- Variant select2 call: -------------------------------- */
        function init_variant_select() {
            $('.variant_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.model.variant.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            model_id: SelectedModel,
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
        }

        /* -------------------------- Customer select call -------------------------- */
        function init_customer_select() {
            $('.customer').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.customer.list') }}",
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
        }

        /* --------------------------- Grande Select2 call -------------------------- */
        function init_grade_select() {
            $('.phone_grade').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.grade.list') }}",
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
        }

        /* ------------------------- Phone Note Select2 call ------------------------ */
        function init_phone_notes_select() {
            $('.phone_notes_type').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone_damages.list') }}",
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
        }

        /* -------------------------- Employee Select2 call ------------------------- */
        function init_employee_select() {
            $('.employee_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.employee.list') }}",
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
        }

        /* ------------------------- Pay Worker select2 call ------------------------ */
        function init_pay_worker_select() {
            $('.pay_worker').select2({
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
        }

        /* ------------------------- Payment Mode select2 call ------------------------ */
        function init_payment_mode_select() {
            $('.payment_mode').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.payment_mode.list') }}",
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
            }).on('select2:unselect', function(e) {
                var data = e.params.data;
                $(this).parent('div').parent('div').parent('div').parent('div').find(
                    `.payment_input_index_${data.id}`).remove();
            });
        }

        /* ---------------------------- Damage (exepense) select2 call --------------------------- */
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
            });
        }

        $(document).ready(function() {
            'use strict';
            $(".input-mask").inputmask();
            init_brand_select();
            init_category_select();
            init_series_select();
            init_model_select();
            init_variant_select();
            init_customer_select();
            init_phone_notes_select();
            init_grade_select();
            init_employee_select();
            init_pay_worker_select();
            init_payment_mode_select();
            init_damage_select();
            // $('.repeater').repeater({
            //     show: function() {
            //         $(this).slideDown();
            //     },
            //     hide: function(deleteElement) {
            //         if (confirm('Are you sure you want to delete this element?')) {
            //             $(this).slideUp(deleteElement);
            //         }
            //     },
            //     ready: function(setIndexes) {

            //     }
            // });

            window.outerRepeater = $('.outer-repeater').repeater({
                isFirstItemUndeletable: true,
                show: function() {
                    $(this).slideDown();
                    $('.outer-repeater').find('.select2-container').remove();
                    init_brand_select();
                    init_category_select();
                    init_series_select();
                    init_model_select();
                    init_variant_select();
                    init_customer_select();
                    init_phone_notes_select();
                    init_grade_select();
                    init_employee_select();
                    init_pay_worker_select();
                    init_payment_mode_select();
                    init_damage_select();
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                repeaters: [{
                    isFirstItemUndeletable: true,
                    selector: '.inner-repeater',
                    show: function() {
                        $(this).slideDown();
                        $('.inner-repeater').find('.select2-container').remove();
                        init_damage_select();
                    },
                    hide: function(deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }]
            });
        });
    </script>
@endsection
