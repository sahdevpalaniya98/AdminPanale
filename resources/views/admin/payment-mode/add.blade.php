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
                    <form action="{{ route('admin.payment.mode.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label" for="payment mode">Payment Mode<span class="text text-danger h5"> *</span></label>
                            <input type="text" class="form-control" name="payment_name" id="payment_name" placeholder="Enter Payment Name">
                            @error('payment_name')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="remark">Remark<span class="text text-danger h5"> *</span></label>
                            <textarea name="remark" class="form-control"  id="remark" ></textarea>
                            @error('remark')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.payment.mode.index') }}" class="btn btn-secondary waves-effect">
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
                    payment_name: {
                        required: true
                    },
                    remark: {
                        required: true
                    },
                },

                messages: {
                    payment_name: {
                        required: "The payment name field is required."
                    },
                    remark: {
                        required: "The remark field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
@endsection
