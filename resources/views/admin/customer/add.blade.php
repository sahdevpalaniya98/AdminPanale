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
                                    <label class="form-label" for="name">Customer Name<span class="text text-danger h5">
                                            *</span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name') }}" maxlength="150" placeholder="Enter Customer Name">
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
                                    <textarea type="text" class="form-control" name="address" id="address" placeholder="Enter Customer Address">{{ old('address') }}</textarea>
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
                                        value="{{ old('mobile_number') }}" maxlength="10"
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
                                    <label class="form-label" for="document">Document Type</label>
                                    <select class="form-select js-example-basic-multiple" name="document[]" id="document_type"
                                        multiple="multiple" placeholder="Please Select Docuemnts">
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

                            <div class="col-md-12 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="remark">Customer Remark</label>
                                    <input type="text" class="form-control" name="remark" id="remark"
                                        value="{{ old('remark') }}" maxlength="10" placeholder="Enter Customer Remark">
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
                            <input type="hidden" readonly class="newimage" name="image" value="">
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
    <script src="{{ asset('js/Add_document_dropzone.js') }}"></script>
@endsection
