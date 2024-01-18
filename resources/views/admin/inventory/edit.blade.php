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
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.inventory.update') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        @isset($inventory)
                            <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                        @endisset

                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="brand_id">Brand<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select" name="brand_id" id="brand_id">
                                        @isset($inventory)
                                            @if ($inventory->phone_brand)
                                                <option selected value="{{ $inventory->brand_id }}">
                                                    {{ $inventory->phone_brand->brand_name }}</option>
                                            @endif
                                        @endisset
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
                                    <label class="form-label" for="category_id">Brand Category<span
                                            class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="category_id" id="category_id">
                                        @isset($inventory)
                                            @if ($inventory->category_id != null)
                                                <option value="{{ $inventory->category_id }}" selected>
                                                    {{ $inventory->category->category_name }}</option>
                                            @endif
                                        @endisset
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
                                    <label class="form-label" for="series_id">Phone Series<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select" name="series_id" id="series_id">
                                        @isset($inventory)
                                            @if ($inventory->phone_serice)
                                                <option selected value="{{ $inventory->series_id }}">
                                                    {{ $inventory->phone_serice->series_name }}</option>
                                            @endif
                                        @endisset
                                    </select>
                                    @error('series_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="model_id">Phone Model<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select" name="model_id" id="model_id">
                                        @isset($inventory)
                                            @if ($inventory->phone_model)
                                                <option selected value="{{ $inventory->model_id }}">
                                                    {{ $inventory->phone_model->model_name }}</option>
                                            @endif
                                        @endisset
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
                                    <label class="form-label" for="variant_id">Phone Variant<span
                                            class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select" name="variant_id[]" id="variant_id" multiple="multiple">
                                        @isset($inventory)
                                            @if ($inventory->inventory_variant != '')
                                                @foreach ($inventory->inventory_variant as $key => $variant)
                                                    <option selected
                                                        value="{{ isset($variant) && $variant->id != '' ? $variant->id : '' }}">
                                                        {{ isset($variant) && $variant->title != '' ? $variant->title : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endisset
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
                                    <label class="form-label" for="customer">Customer<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-control" name="customer" id="customer">
                                        @isset($inventory)
                                            <option selected value="{{ $inventory->customer_id }}">
                                                {{ $inventory->customer->name }}</option>
                                        @endisset
                                    </select>
                                    @error('customer')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="purchase_price">Purchase Price</label>
                                    <input id="purchase_price" name="purchase_price"
                                        value="{{ old('purchase_price', $inventory->purchase_price ?? '') }}"
                                        class="form-control input-mask text-start"
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
                                    <select style="width:100%;" class="form-select" name="phone_grade" id="phone_grade"
                                        placeholder="Please Select Phone Grade">
                                        @isset($inventory)
                                            @if ($inventory->phone_grade)
                                                <option selected value="{{ $inventory->phone_grade_id }}">
                                                    {{ $inventory->phone_grade->grade_name }}</option>
                                            @endif
                                        @endisset
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
                                    <select style="width:100%;" class="form-select" name="phone_notes[]"
                                        id="phone_notes_type" multiple="multiple"
                                        placeholder="Please Select Phone Notes">
                                        @isset($inventory)
                                            @foreach ($inventory->phone_damages as $key => $phone_damage)
                                                <option selected
                                                    value="{{ isset($phone_damage) && $phone_damage->id != '' ? $phone_damage->id : '' }}">
                                                    {{ isset($phone_damage) && $phone_damage->damage_name != '' ? $phone_damage->damage_name : '' }}
                                                </option>
                                            @endforeach
                                        @endisset
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
                                    <select class="form-control" name="employee_id" id="employee_id">
                                        @isset($inventory)
                                            <option selected value="{{ $inventory->employee_id }}">
                                                {{ $inventory->employee->name }}</option>
                                        @endisset
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
                                    <select class="form-select select2" name="pay_worker_id" id="pay_worker">
                                        @isset($inventory)
                                            <option selected value="{{ $inventory->pay_worker_id }}">
                                                {{ (isset($inventory->pay_worker) && $inventory->pay_worker != "") ? $inventory->pay_worker->name : "" }}</option>
                                        @endisset
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
                                    <select multiple class="form-control" name="payment_mode[]" id="payment_mode">
                                        @isset($inventory)
                                            @foreach ($inventory->payment_modes as $key => $payment_mode)
                                                <option selected
                                                    value="{{ isset($payment_mode) && $payment_mode->id != '' ? $payment_mode->id : '' }}">
                                                    {{ isset($payment_mode) && $payment_mode->payment_name != '' ? $payment_mode->payment_name : '' }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('payment_mode')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row" id="payment_type_inputs">
                            @isset($inventory)
                                @if ($inventory->payment_modes)
                                    @foreach ($inventory->payment_modes as $key => $payment_mode_amount)
                                        <div class="col-4"
                                            id="payment_input_index_{{ isset($payment_mode_amount) && $payment_mode_amount->id != '' ? $payment_mode_amount->id : '' }}">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="model_id">${{ isset($payment_mode) && $payment_mode->payment_name != '' ? $payment_mode->payment_name : '' }}
                                                    Amount<span class="text text-danger h5"> *</span></label>
                                                <input
                                                    id="payment_mode_amount_{{ isset($payment_mode_amount) && $payment_mode_amount->id != '' ? $payment_mode_amount->id : '' }}"
                                                    name="payment_mode_amount[]"
                                                    value="{{ isset($payment_mode_amount) && $payment_mode_amount->pivot != '' ? $payment_mode_amount->pivot->amount : '' }}"
                                                    class="form-control input-mask text-start"
                                                    data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endisset
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="serial_number">Serial Number<span
                                            class="text text-danger h5"> *</span></label>
                                    <input id="serial_number" name="serial_number"
                                        value="{{ isset($inventory) && $inventory->serial_number != '' ? $inventory->serial_number : old('serial_number') }}"
                                        class="form-control input-mask text-start" />
                                </div>
                            </div>
                            @php
                                $bettryclass = 'd-none';
                                if (isset($inventory) && $inventory->phone_grade_id != '') {
                                    if (isset($inventory->phone_grade->id) && $inventory->phone_grade->id != '') {
                                        if ($inventory->phone_grade->id == 2 || $inventory->phone_grade->id == 3) {
                                            $bettryclass = '';
                                        }
                                    }
                                }
                            @endphp
                            <div class="col-4 bettry_health_div {{ $bettryclass }}">
                                <div class="mb-3">
                                    <label class="form-label" for="bettry_health">Bettry Health<span
                                            class="text text-danger h5"> *</span></label>
                                    <input required type="text" class="form-control bettry_health" id="bettry_health"
                                        name="bettry_health"
                                        value="{{ isset($inventory) && $inventory->bettry_health != '' ? $inventory->bettry_health : old('bettry_health') }}" />
                                </div>
                            </div>
                        </div>

                        <div class='card-header mb-3 text-center'>
                            <h5><b>Expenses</b></h5>
                        </div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class="repeater">
                                    <div class="col-12" data-repeater-list="damages">
                                        @if (isset($inventory) && $inventory->expenses->isNotEmpty())
                                            @foreach ($inventory->expenses as $key => $expense)
                                                <div data-repeater-item class="row mb-3"
                                                    style="display:flex;align-items: end;">
                                                    <div class="col-5">
                                                        <label class="form-label" for="select-damage">Expense</label>
                                                        <select class="form-select damage_id" name="damage_id">
                                                            <option selected
                                                                value="{{ isset($expense) && $expense->id != null ? $expense->id : '' }}">
                                                                {{ isset($expense) && $expense->expense_name != null ? $expense->expense_name : '' }}
                                                            </option>
                                                        </select>
                                                        @error('damage_id')
                                                            <span class="text text-danger" role="alert">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                        <input type="hidden" name="expense_name" class="expense_name"
                                                            value="{{ isset($expense) && $expense->expense_name != null ? $expense->expense_name : '' }}" />
                                                    </div>
                                                    <div class="col-5">
                                                        <label class="form-label" for="damage-amount">Expense
                                                            Amount</span></label>
                                                        <input type="text"
                                                            value="{{ isset($expense) && $expense->pivot != '' ? $expense->pivot->amount : '' }}"
                                                            class="form-control input-mask damage_amount calculate_amount inventory-items-expense-amt text-start"
                                                            name="damage_amount"
                                                            data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'" />
                                                        @error('damage_amount')
                                                            <span class="text text-danger" role="alert">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    {{-- <div class="col-1" style="margin-top:32px">
                                                    <button type="button" class="view_invetory btn btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div> --}}
                                                    <div class="col-2">
                                                        <button data-repeater-delete type="button"
                                                            class="view_invetory btn btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div data-repeater-item class="row mb-3"
                                                style="display:flex;align-items: end;">
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
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <input data-repeater-create type="button" value="Add"
                                            class="btn btn-success mt-3 mt-lg-0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="errors-container" class="mb-3"></div>
                        @php
                            $class = '';
                            $brand_name = '';
                            if (isset($inventory) && $inventory->phone_brand != '') {
                                $brand_name = strtolower($inventory->phone_brand->brand_name);
                                if ($brand_name === 'apple' || $brand_name === 'samsung') {
                                    $class = '';
                                } else {
                                    $class = 'd-none';
                                }
                            }
                        @endphp
                        <div class="mb-3 get_sickw_data">
                            <input type="hidden" name="selected_brand_text" id="selected_brand_text"
                                value="{{ $brand_name }}" />
                            <label class="form-label flex" for="name">
                                <span class="title">Phone SICKW Details</span><span class="text text-danger h5"> *</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="imei_number" id="imei_number"
                                    value="{{ isset($inventory) && $inventory->imei_number != '' ? $inventory->imei_number : old('imei_number') }}"
                                    maxlength="150" placeholder="Enter IMEI Number">
                                {{-- <button type="button" id="get_sickw_button"
                                    class="btn btn-primary {{ $class }}">Get
                                    Data</button> --}}
                            </div>
                            <input type="hidden"
                                value="{{ old('phone_sickw_details', $inventory->phone_sickw_details ?? '') }}"
                                name="phone_sickw_details" id="phone_sickw_details" />
                            @error('phone_sick')
                                <span class="text text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                            <div id="sickw_details">
                                @isset($inventory)
                                    @if ($inventory->phone_sickw_details != null)
                                        <div class="sickw-details-container">
                                            {!! $inventory->phone_sickw_details !!}
                                        </div>
                                    @endif
                                @endisset
                            </div>
                        </div>


                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="add_inventory_submit_btn"
                                class="btn btn-primary waves-effect waves-light">
                                @if (isset($inventory))
                                    Update
                                @else
                                    Save
                                @endif
                            </button>
                            <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary waves-effect">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal fade" id="add_customer_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <form action="{{ route('admin.customer.store') }}" name="addCustomerFrm" id="addCustomerFrm"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Customer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Customer Name<span
                                                    class="text text-danger h5">
                                                    *</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                value="{{ old('name') }}" maxlength="150"
                                                placeholder="Enter Customer Name">
                                            @error('name')
                                                <span class="text text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Address</label>
                                            <textarea type="text" class="form-control" name="address" id="address" placeholder="Enter Customer Address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="text text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="mobile_number">Customer Phone no</label>
                                            <input type="text" class="form-control" name="mobile_number"
                                                id="mobile_number" value="{{ old('mobile_number') }}" maxlength="10"
                                                placeholder="Enter Customer Phone Number">
                                            @error('mobile_number')
                                                <span class="text text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="document">Document Type</label>
                                            <select style="width:100%;" class="form-select" name="document[]"
                                                id="document_type" multiple="multiple"
                                                placeholder="Please Select Docuemnts">
                                                <option value="addhar_card">Aadhaar card</option>
                                                <option value="passport">Passport</option>
                                                <option value="brith_certificate">Birth certificate</option>
                                                <option value="account_number">Permanent account number</option>
                                                <option value="ration_card">Ration card</option>
                                                <option value="driving_licence">Driving licence</option>
                                                <option value="marriage_certificate">Marriage certificate</option>
                                                <option value="rental_agreement">Rental agreement</option>
                                                <option value="passbook">Passbook</option>
                                            </select>
                                            @error('document')
                                                <span class="text text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="remark">Customer Remark</label>
                                            <input type="text" class="form-control" name="remark" id="remark"
                                                value="{{ old('remark') }}" maxlength="10"
                                                placeholder="Enter Customer Remark">
                                            @error('remark')
                                                <span class="text text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-12 mb-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="dropzone">Document Upload</label>
                                        <div class="dropzone" id="dropzone"></div>
                                        <input type="hidden" readonly class="newimage" name="image" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex flex-wrap gap-2 fr-button">
                                    <button type="submit" id="submit_data"
                                        class="btn btn-primary waves-effect waves-light">
                                        Save
                                    </button>
                                    <button type="button" data-bs-dismiss="modal"
                                        class="btn btn-secondary waves-effect cancle">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </form>
                </div><!-- /.modal-dialog -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="https://themesbrand.com/skote/layouts/assets/libs/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    <script src="https://themesbrand.com/skote/layouts/assets/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script type="text/javascript">
        Dropzone.options.dropzone = {
            url: $('#addCustomerFrm').attr('action'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            autoProcessQueue: false,
            parallelUploads: 15,
            uploadMultiple: true,
            maxFilesize: 20,
            dictFileTooBig: "File too Big, please select a file less than 20mb",
            addRemoveLinks: true,
            init: function() {
                myDropzone = this; // Makes sure that 'this' is understood inside the functions below.

                // for Dropzone to process the queue (instead of default form behavior):

                document.getElementById("submit_data").addEventListener("click", function(e) {
                    if (myDropzone.getQueuedFiles().length > 0) {
                        e.preventDefault();
                        e.stopPropagation();
                        myDropzone.processQueue();
                    } else {
                        myDropzone.processQueue();
                        form.submit();
                    }
                });

                //send all the form data along with the files:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    formData.append("name", jQuery("input[name='name']").val());
                    formData.append("address", jQuery("input[name='address']").val());
                    formData.append("mobile_number", jQuery("input[name='mobile_number']").val());
                    formData.append("document", jQuery("#document_type").val());
                    formData.append("remark", jQuery($('#remark').val()).val());
                });
            },
            success: function(file, response) {
                if (response.success == true) {
                    $('#customer').html(
                        `<option selected value="${response.data.id}">${response.data.name}</option>`);
                    $('#add_customer_modal').modal('hide');
                    swal_success('Saved!', response.message);
                    reset_validation(form);
                } else {
                    if (response.validation_error) {
                        server_validation_error('.error_container', response.message);
                    } else {
                        swal_error(response.message);
                    }
                }
            }
        }
        var selected_brand_text;
        $('#get_sickw_button').on('click', function() {
            const btn = $(this);
            const btn_html = btn.html();
            const imei_number = $('#imei_number').val();
            let brand_value = 0;
            selected_brand_text = $("#selected_brand_text").val();
            if (imei_number !== '') {
                if (selected_brand_text === 'apple') {
                    brand_value = 1;
                } else if (selected_brand_text === 'samsung') {
                    brand_value = 2;
                }
                $(this).attr('disabled', true).html('Loading...');
                $.when(ajax_request("{{ route('admin.ajax.get_sickw') }}", {
                        imei_number,
                        brand_value
                    }))
                    .done(function(response) {
                        btn.removeAttr('disabled').html(btn_html);
                        if (response.success == true) {
                            $("#get_sickw_button").attr('disabled', true);
                            $('#phone_sickw_details').val(response.data.result);
                            $('#sickw_details').html(
                                `<div class="sickw-details-container">${response.data.result}</div>`);
                        } else {
                            if (response.validation_error) {
                                server_validation_error('.error_container', response.message);
                            } else {
                                swal_error(response.message);
                            }
                        }
                    }).fail(function(jqXHR, status, exception) {
                        btn.removeAttr('disabled').html(btn_html);
                        ajax_fail(jqXHR, status, exception);
                    });
            }
        });
        var customer_type_select;
        var inventory_form_validator;

        function addCustomer() {
            $("#addCustomerFrm").validate({
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
                    name: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "The name field is required."
                    },
                },
                submitHandler: function(form) {
                    var form_data = $(form).serialize();
                    $.when(ajax_request($(form).attr('action'), form_data))
                        .done(function(response) {
                            if (response.success == true) {
                                $('#customer').html(
                                    `<option selected value="${response.data.id}">${response.data.name}</option>`
                                );
                                $('#add_customer_modal').modal('hide');
                                swal_success('Saved!', response.message);
                                reset_validation(form);
                            } else {
                                if (response.validation_error) {
                                    server_validation_error('.error_container', response.message);
                                } else {
                                    swal_error(response.message);
                                }
                            }
                        }).fail(function(jqXHR, status, exception) {
                            ajax_fail(jqXHR, status, exception);
                        });
                }
            });
            var a = $(customer_type_select).data('select2');
            a.trigger('close');
        }

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

        $('#addfrm').submit(function() {
            const purchase_price = parseInt($('#purchase_price').val().replace('$ ', ''));
            const payment_partial_price = $('#payment_mode').val();
            let total_partial_amount = 0;
            if (payment_partial_price.length > 0) {
                payment_partial_price.map(function(data) {
                    total_partial_amount += parseInt($(`#payment_mode_amount_${data}`).val().replace('$ ',
                        ''))
                });
            }
            if (total_partial_amount === purchase_price) {
                $('#errors-container').html('');
                return true;
            } else {
                $('#errors-container').html(
                    '<span class="text text-danger" role="alert">Partial amount should be equal to purchase amount.</span>'
                );
                return false;
            }
        });

        $("#phone_grade").on('change', function() {
            if ($(this).val() == 2 || $(this).val() == 3) {
                $(".bettry_health_div").removeClass("d-none");
            } else {
                $("#bettry_health").val('');
                $(".bettry_health_div").addClass("d-none");
            }
        })

        $(document).ready(function() {
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
                    } else if (element.attr("name") == "imei_number") {
                        error.insertAfter('.input-group');
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    brand_id: {
                        required: true
                    },
                    series_id: {
                        required: true
                    },
                    model_id: {
                        required: true
                    },
                    customer: {
                        required: true
                    },
                    phone_name: {
                        required: true
                    },
                    imei_number: {
                        required: true,
                    },
                    purchase_price: {
                        required: true
                    },
                    'phone_notes[]': {
                        required: true
                    },
                    'payment_mode[]': {
                        required: true
                    },
                    'payment_mode_amount[]': {
                        required: true
                    },
                    employee_id: {
                        required: true
                    },
                    serial_number: {
                        required: true
                    },
                    phone_sickw_details: {
                        required: function(element) {
                            if (selected_brand_text === 'apple' || selected_brand_text === 'samsung') {
                                return true;
                            } else {
                                return false;

                            }
                        }
                    },
                    bettry_health: {
                        required: function(element) {
                            if ($("#phone_grade").val() == 2 || $("#phone_grade").val() == 3) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                },

                messages: {
                    imei_number: {
                        required: "The imei field is required."
                    },
                    serial_number: {
                        required: "The serial number field is required."
                    },
                    phone_sickw_details: {
                        required: "The phone sickw details field is required."
                    },
                    bettry_health: {
                        required: "The phone bettry health field is required."
                    }
                },
                // submitHandler: function(e) {
                //     e.submit();
                // }
            });
            $(".input-mask").inputmask();
            $('#document_type').select2({
                dropdownParent: $('#add_customer_modal')
            });
            customer_type_select = $('#customer').select2({
                width: '100%',
                language: {
                    noResults: function() {
                        return `<button style="width: 100%" type="button" class="btn btn-primary" onClick='addCustomer()' data-bs-toggle="modal" data-bs-target="#add_customer_modal">+ Add New Customer</button></li>`;
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
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

            $('#phone_notes_type').select2({
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

            $('#phone_grade').select2({
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

            $('#payment_mode').select2({
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
            }).on('select2:select', function(e) {
                var data = e.params.data;
                const values = $('#payment_mode').val();
                $('#payment_type_inputs').append(`
                    <div class="col-4" id="payment_input_index_${data.id}">
                        <div class="mb-3">
                            <label class="form-label" for="model_id">${data.text} Amount<span class="text text-danger h5"> *</span></label>
                            <input id="payment_mode_amount_${data.id}" name="payment_mode_amount[]" class="form-control input-mask text-start" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'">
                        </div>
                    </div>
                `);
                $(".input-mask").inputmask()
            }).on('select2:unselect', function(e) {
                var data = e.params.data;
                $('#payment_type_inputs').find(`#payment_input_index_${data.id}`).remove();
            });

            $('#employee_id').select2({
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
            $('#brand_id').on('change', function() {
                select_brandval($(this).select2('data'));
                $("#series_id").val(null).trigger('change');
                $("#model_id").val(null).trigger("change");
                $("#category_id").val(null).trigger('change');
                $("#variant_id").val(null).trigger('change');
                $("#get_sickw_button").attr('disabled', false);
            })

            function select_brandval(value) {
                var data = value;
                if (data) {
                    if (data[0] && data[0].text.toLowerCase() != "") {
                        selected_brand_text1 = data[0].text.toLowerCase();
                        selected_brand_text = selected_brand_text1.trim();
                        $("#selected_brand_text").val(selected_brand_text);
                        if (selected_brand_text === 'apple' || selected_brand_text === 'samsung') {
                            $("#get_sickw_button").removeClass("d-none");
                            $(".get_sickw_data").find('label').find(".title").text(
                                'Phone SICKW Details');
                        } else {
                            $("#imei_number").val('');
                            $(".get_sickw_data").find('label').find(".title").text(
                                'Phone ImeI number');
                            $("#phone_sickw_details").val('');
                            $("#get_sickw_button").addClass("d-none");
                        }
                    }
                }
            }
            $('#category_id').on('change', function() {
                $("#series_id").val(null).trigger('change');
            })

            $('#series_id').on('change', function() {
                $("#model_id").val(null).trigger("change");
            })

            $('#model_id').on('change', function() {
                $("#variant_id").val(null).trigger("change");
            })

            $('#brand_id').select2({
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
            });

            $('#category_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.category.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            brand_id: $('#brand_id').val(),
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

            $('#series_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.series.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            category_id: $('#category_id').val(),
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

            $('#model_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.model.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            brand_id: $('#brand_id').val(),
                            series_id: $('#series_id').val(),
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

            $('#variant_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.model.variant.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term,
                            model_id: $('#model_id').val(),
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
        });

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
            init_damage_select();
            var $repeater = $('.repeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                    $('.repeater').find('.select2-container').remove();
                    init_damage_select();
                    // $(".input-mask").inputmask();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function(setIndexes) {},
                isFirstItemUndeletable: true,
            });
        });
    </script>
@endsection
