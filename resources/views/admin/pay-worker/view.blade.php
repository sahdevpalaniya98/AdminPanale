@extends('layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('content')
    <input type="hidden" id="pay_worker_id" value="{{ $pay_worker_id }}">
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
                                        <th>Pay Worker</th>
                                        <th>Pay Worker Payment</th>
                                        <th>Sell Amount</th>
                                        <th>Order Status</th>
                                        <th>Created At</th>
                                        {{-- <th>Action</th> --}}
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
        $(document).ready(function() {
            var pay_worker_id = $('#pay_worker_id').val();

            var table;
            var url = '{!! route('admin.pay.worker.data.view') !!}';

            var columns = [
                { data: "id", name: "id" },
                { data: 'pay_worker_id', name: 'pay_worker_id' },
                { data: 'pay_worker_payment', name: 'pay_worker_payment' },
                { data: 'sell_amount', name: 'sell_amount' },
                { data: 'order_status', name: 'order_status' },
                { data: 'created_at', name: 'created_at' },
                // { data: 'action', name: 'action', sortable: false}
            ];

            createDataTable(url, columns, ['pay_worker_id']);
        });
    </script>
@endsection
