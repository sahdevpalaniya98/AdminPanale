@extends('layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.customer.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}" />
                                    <label class="form-label" for="name">Customer Name<span class="text text-danger h5">
                                            *</span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $customer->name }}" maxlength="150" placeholder="Enter Customer Name">
                                    @error('name')
                                        <span class="text text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <textarea type="text" class="form-control" name="address" id="address" placeholder="Enter Customer Address">{{ (isset($customer->address) && $customer->address) ? $customer->address : ""}}</textarea>
                                    @error('address')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label" for="mobile_number">Customer Phone no</label>
                                    <input type="text" class="form-control" name="mobile_number" id="mobile_number"
                                        value="{{ (isset($customer->mobile_number) && $customer->mobile_number != "") ? $customer->mobile_number : "" }}" maxlength="10"
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
                            <div class="col-md-12 col-lg-6">
                                <div class="mb-3">
                                    @php
                                        $docuemnt_val = explode(',', $customer->document);
                                    @endphp
                                    <label class="form-label" for="document">Document Type</label>
                                    <select class="form-select js-example-basic-multiple" name="document[]"
                                        id="document_type" multiple="multiple" placeholder="Please Select Docuemnts">
                                        <option value="addhar_card"
                                            {{ in_array('addhar_card', $docuemnt_val) ? 'selected' : '' }}>Aadhaar card
                                        </option>
                                        <option value="docuemnt_val"
                                            {{ in_array('docuemnt_val', $docuemnt_val) ? 'selected' : '' }}>Passport
                                        </option>
                                        <option value="brith_certificate"
                                            {{ in_array('brith_certificate', $docuemnt_val) ? 'selected' : '' }}>Birth
                                            certificate</option>
                                        <option
                                            value="account_number"{{ in_array('account_number', $docuemnt_val) ? 'selected' : '' }}>
                                            Permanent account number</option>
                                        <option value="ration_card"
                                            {{ in_array('ration_card', $docuemnt_val) ? 'selected' : '' }}>Ration card
                                        </option>
                                        <option value="driving_licence"
                                            {{ in_array('driving_licence', $docuemnt_val) ? 'selected' : '' }}>Driving
                                            licence</option>
                                        <option value="marriage_certificate"
                                            {{ in_array('marriage_certificate', $docuemnt_val) ? 'selected' : '' }}>
                                            Marriage certificate</option>
                                        <option value="rental_agreement"
                                            {{ in_array('rental_agreement', $docuemnt_val) ? 'selected' : '' }}>Rental
                                            agreement</option>
                                        <option value="passbook"
                                            {{ in_array('passbook', $docuemnt_val) ? 'selected' : '' }}>Passbook</option>
                                    </select>
                                    @error('document')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="remark">Customer Remark</label>
                                    <input type="text" class="form-control" name="remark" id="remark"
                                        value="{{ $customer->remark }}" maxlength="10" placeholder="Enter Customer Remark">
                                    @error('remark')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 mb-4">
                            <label class="form-label" for="dropzone">Document Upload</label>
                            <div class="dropzone" id="dropzone"></div>
                            <input type="hidden" readonly class="removed_images" id="removed_images" name="removed_images"
                                value="">
                            <input type="hidden" readonly class="old_img" id="old_img" name="old_img"
                                value="{{ $customer->document_images }}">
                            <input type="hidden" readonly class="imageurl" id="imageurl"
                                value="{{ asset('uploads/documentImages') }}">
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary waves-effect cancle">
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });

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
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
    <script src="{{ asset('js/Edit_document_dropzone.js') }}"></script>
@endsection
