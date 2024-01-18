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
                    <form action="{{ route('admin.pay.worker.store') }}" name="addfrm" id="addfrm" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="company name">Name<span class="text text-danger h5"> *</span></label>
                                    <input type="text" class="form-control" name="name" id="name"  placeholder="Enter Pay Worker  Name">
                                    @error('name')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="phone number">Phone Number<span class="text text-danger h5"> *</span></label>
                                    <input type="tel" class="form-control" name="phone_number" id="phone_number"  maxlength="10" placeholder="Enter Company Name">
                                    @error('phone_number')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
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
