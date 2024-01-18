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
                    <form action="{{ route('admin.payment.mode.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                       
                        <div class="mb-3">
                            <input type="hidden" name="payment_mode_id" value="{{ $payment_mode->id }}" />
                            <label class="form-label" for="payment name">Payment Name<span class="text text-danger h5">
                                    *</span></label>
                            <input type="text" class="form-control" name="payment_name" id="payment_name"
                                value="{{ $payment_mode->payment_name }}" placeholder="Enter payment name Name">
                            @error('payment_name')
                                <span class="text text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                            
                        <div class="mb-3">
                            <label class="form-label" for="remark">remark</label>
                            <textarea type="text" class="form-control" name="remark" id="remark" placeholder="Enter Remark ">{{ (isset($payment_mode->remark) && $payment_mode->remark) ? $payment_mode->remark : ""}}</textarea>
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
                            <a href="{{ route('admin.payment.mode.index') }}" class="btn btn-secondary waves-effect cancle">
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
                    payment_name: {
                        required: true
                    },
                    remark: {
                        required: true
                    },
                },
                messages: {
                    expense_name: {
                        required: "The payment name field is required."
                    },
                    note: {
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
