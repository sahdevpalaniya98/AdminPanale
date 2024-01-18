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
            <div class="card" style="margin-bottom: 12px;">
                <div class="card-body">
                    <form action="#" name="addfrm" id="addfrm" method="POST">
                        @csrf
                        <input type="hidden" readonly name="pay_worker_id" id="pay_worker_id" value="{{ $pay_worker_id }}" />
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group @error('amount_type') is-invalid @enderror">
                                    <label class="control-label">Amount Type<span style="color: red">*</span></label>
                                    <div class="radio-list">
                                        <label class="radio-inline me-2">
                                            <div class="form-check form-radio-primary mb-3">
                                                <input type="radio" class="form-check-input amount_type"
                                                    name="amount_type" id="active-radio" value="CREDIT" checked>
                                                <label class="form-check-label" for="active-radio">CREDIT</label>
                                            </div>
                                        </label>
                                        <label class="radio-inline">
                                            <div class="form-check form-radio-primary mb-3">
                                                <input type="radio" class="form-check-input amount_type"
                                                    name="amount_type" id="inactive-radio" value="DEBIT">
                                                <label class="form-check-label" for="inactive-radio">DEBIT</label>
                                            </div>
                                        </label>
                                        @error('amount_type')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="company name">Amount<span class="text text-danger h5">
                                            *</span></label>
                                    <input type="text" class="form-control" name="amount" id="amount"
                                        placeholder="Enter Pay Worker Amount">
                                    @error('Amount')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" class="btn btn-primary waves-effect waves-light save">
                                Save
                            </button>
                            <a href="{{ route('admin.pay.worker.index') }}" class="btn btn-secondary waves-effect">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <h4><b>Wallet : </b><span class="{{ $total_wallet_amount > 0 ? 'text-success' : 'text-danger' }}" id="total_wallet_amount">${{ $total_wallet_amount }}</span></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Payment Notes</th>
                                        <th>Payment Amount</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        var table;
        $(document).ready(function() {
            setTimeout(function() {
                $(".invalid-feedback").hide();
            }, 7000);
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
                    amount_type: {
                        required: true
                    },
                    amount: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    amount_type: {
                        required: "The amount type field is required."
                    },
                    amount: {
                        required: "The amount field is required."
                    },
                },
                submitHandler: function(e) {
                    var formData = {
                        pay_worker_id: $("#pay_worker_id").val(),
                        amount_type: $('input[name="amount_type"]:checked').val(),
                        amount: $("#amount").val(),
                    };
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.pay.worker.wallet_store') }}",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        encode: true,
                    }).done(function(data) {
                        if ((data.error)) {
                            for (let index = 0; index < data.error.length; index++) {
                                Toast.fire({
                                    icon: "error",
                                    title: data.error[index]
                                })
                            }
                        }

                        if (data.status) {
                            Toast.fire({
                                icon: "success",
                                title: data.message
                            });
                            $("#amount").val('');
                            $("#total_wallet_amount").text("$" + data.total_wallet_amount);
                            table.ajax.reload()
                        }
                    });
                }
            });

            var id = $("#pay_worker_id").val();
            var url = "{{ route('admin.pay.worker.wallet_table') }}";
            table = $('#dataTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.id = id;
                    },
                    url: url,
                    type: 'POST',
                },
                columns: [{
                        data: "id",
                        name: "id"
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ],
                order: [0, 'desc']
            });
            return table;
        });
    </script>
@endsection
