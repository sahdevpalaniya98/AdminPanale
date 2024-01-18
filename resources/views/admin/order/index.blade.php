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
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Buyer</th>
                                        <th>Pay Worker </th>
                                        <th>Sell Amount</th>
                                        <th>Order Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="modal fade" id="order_complete_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <form action="{{ route('admin.order.mark_as_complete') }}" name="markAsCompleteOrderForm" id="markAsCompleteOrderForm" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Complete the order</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="hidden" id="order_id" name="order_id" />
                                        <input type="hidden" id="selling_price" name="selling_price" />
                                        <div class="mb-3">
                                            <label class="form-label" for="payment_mode">Payment Mode<span class="text text-danger h5"> *</span></label>
                                            <select class="form-control" name="payment_mode[]" multiple id="payment_mode"></select>
                                            @error('payment_mode')
                                                <span class="text text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="row" id="payment_type_inputs">
                                        </div>
                                    </div>
                                </div>
                                <div id="errors-container"></div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex flex-wrap gap-2 fr-button">
                                    <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                        Save
                                    </button>
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary waves-effect cancle">
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
    <script>
        $(document).ready(function() {
            var table;
            var url = '{!! route('admin.order.data') !!}';

            var columns = [
                { data: "id", name: "id" },
                { data: 'buyer_id', name: 'buyer_id' },
                { data: 'pay_worker_id', name: 'pay_worker_id' },
                { data: 'sell_amount', name: 'sell_amount' },
                { data: 'order_status', name: 'order_status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', sortable: false, searchable: false}
            ];
            table = createDataTable(url, columns);
        });
        $('#order_complete_modal').on('hidden.bs.modal', function () {
            reset_validation($('#markAsCompleteOrderForm'));
            $('#errors-container').html('');
            $('#payment_type_inputs').html('');
        });
        $(document).on('click', '.return_order', function() {
            const id = $(this).attr('data-id');
            const url = $(this).attr('data-url');
            Swal.fire({
                title: 'Enter Reason',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Return Order',
                showLoaderOnConfirm: true,
                preConfirm: (return_reason) => {
                    if (!return_reason) {
                        Swal.showValidationMessage('Please enter reason.')
                    } else {
                        $.when(ajax_request(url,{ id, return_reason }))
                        .done(function(response){
                            if(response.success == true){
                                table.ajax.reload();
                                swal_success('Returned!',response.message);
                            }else{
                                if(response.validation_error){
                                    server_validation_error('.error_container',response.message);
                                }else{
                                    swal_error(response.message);
                                }
                            }
                        }).fail(function(jqXHR, status, exception){
                            ajax_fail(jqXHR, status, exception);
                        });
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {})
        });
        $(document).on('click', '.cancel_order', function() {
            const id = $(this).attr('data-id');
            const url = $(this).attr('data-url');
            Swal.fire({
                title: 'Are you sure ?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Cancel Order',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.when(ajax_request(url,{ id }))
                    .done(function(response){
                        if(response.success == true){
                            table.ajax.reload();
                            swal_success('Cancelled!',response.message);
                        }else{
                            if(response.validation_error){
                                server_validation_error('.error_container',response.message);
                            }else{
                                swal_error(response.message);
                            }
                        }
                    }).fail(function(jqXHR, status, exception){
                        ajax_fail(jqXHR, status, exception);
                    });
                }
            })
        });
        $('#payment_mode').select2({
            width: '100%',
            dropdownParent: $('#order_complete_modal'),
            ajax: {
                url: "{{ route('admin.ajax.payment_mode.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search:term.term
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
        }).on('select2:select', function (e) {
                var data = e.params.data;
                const values = $('#payment_mode').val();
                $('#payment_type_inputs').append(`
                    <div class="col-12" id="payment_input_index_${data.id}">
                        <div class="mb-3">
                            <label class="form-label" for="model_id">${data.text} Amount<span class="text text-danger h5"> *</span></label>
                            <input id="payment_mode_amount_${data.id}" name="payment_mode_amount[]" class="form-control input-mask text-start" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'">
                        </div>
                    </div>
                `);
                $(".input-mask").inputmask()
            }).on('select2:unselect', function (e) {
                var data = e.params.data;
                $('#payment_type_inputs').find(`#payment_input_index_${data.id}`).remove();
            });
        $(document).on('click', '.btnCompleteOrder', function () {
            var id = $(this).data('id');
            var selling_price = $(this).data('selling-price');
            $('#order_id').val(id);
            $('#selling_price').val(selling_price);
            $("#markAsCompleteOrderForm").validate({
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
                    'payment_mode[]': {
                        required: true
                    },
                    'payment_mode_amount[]': {
                        required: true
                    },
                },
                messages: {
                    payment_mode: {
                        required: "The payment mode field is required."
                    },
                },
                submitHandler: function(form) {
                    const purchase_price = parseInt($('#selling_price').val().replace('$ ', ''));
                    const payment_partial_price = $('#payment_mode').val();
                    let total_partial_amount = 0;
                    if (payment_partial_price.length > 0) {
                        payment_partial_price.map(function(data){
                            total_partial_amount += parseInt($(`#payment_mode_amount_${data}`).val().replace('$ ', ''))
                        });
                    }
                    if (total_partial_amount === purchase_price) {
                        $('#errors-container').html('');
                        var form_data = $(form).serialize();
                        $.when(ajax_request($(form).attr('action'),form_data))
                        .done(function(response){
                            if(response.success == true){
                                table.ajax.reload();
                                swal_success('Saved!',response.message);
                                reset_validation(form);
                                $('#order_complete_modal').modal('toggle');
                            }else{
                                if(response.validation_error){
                                    server_validation_error('.error_container',response.message);
                                }else{
                                    swal_error(response.message);
                                }
                            }
                        }).fail(function(jqXHR, status, exception){
                            ajax_fail(jqXHR, status, exception);
                        });
                    } else {
                        $('#errors-container').html('<span class="text text-danger" role="alert">Partial amount should be equal to selling amount.</span>');
                        return false;
                    }
                }
            });
        });
    </script>
@endsection
