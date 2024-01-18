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
                    <form action="{{ route('admin.buyer.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        @isset($buyer)
                            <input type="hidden" name="buyer_id" value="{{ $buyer->id }}">
                        @endisset

                        <div class="mb-3">
                            <label class="form-label" for="company name">Company Name<span class="text text-danger h5"> *</span></label>
                            <input type="text" class="form-control" name="company_name" id="company_name" value="{{ old('company_name', isset($buyer) ? $buyer->company_name : '') }}" maxlength="150" placeholder="Enter Company Name">
                            @error('company_name')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="company number">Company Number<span class="text text-danger h5"> *</span></label>
                            <input type="tel" class="form-control" name="company_number" id="company_number"
                            value="{{ old('company_number', isset($buyer) ? $buyer->company_number : '') }}" maxlength="150" placeholder="Enter Company Number">
                            @error('company_number')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="contact person name">Contact Person Name <span class="text text-danger h5"> *</span></label>
                            <input type="text" class="form-control" name="contact_person_name" id="contact_person_name"
                            value="{{ old('contact_person_name', isset($buyer) ? $buyer->contact_person_name : '') }}" maxlength="150" placeholder="Enter Company Number">
                            @error('contact_person_name')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="contact person mobile number">contact person mobile number <span class="text text-danger h5"> *</span></label>
                            <input type="tel" class="form-control" name="contact_person_mobile_number" id="contact_person_mobile_number"
                            value="{{ old('contact_person_mobile_number', isset($buyer) ? $buyer->contact_person_mobile_number : '') }}" maxlength="150" placeholder="Enter Contact Person Mobile Number">
                            @error('contact_person_mobile_number')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="address">Address</label>
                            <textarea type="text" class="form-control" name="address" id="address" placeholder="Enter Company Address">{{ old('address', isset($buyer) ? $buyer->address : '') }}</textarea>
                            @error('address')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.buyer.index') }}" class="btn btn-secondary waves-effect">
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
                    company_name: {
                        required: true
                    },
                    company_number: {
                        required: true
                    },
                    contact_person_name: {
                        required: true
                    },
                    contact_person_mobile_number: {
                        required: true
                    },
                },

                messages: {
                    company_name: {
                        required: "The company name field is required."
                    },
                    company_number: {
                        required: "The company number field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
@endsection
