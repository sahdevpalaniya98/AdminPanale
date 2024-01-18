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
                <div class="card-body p-b-0" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-6">
                            <div class="media d-flex justify-content-start">
                                <div class="media-body">
                                    <div class="text-muted" style="max-width: 250px;">
                                        <h5>
                                            Incoming Amount:  <span class="text-warning">+ ${{number_format($inComingAmount, 2)}}</span>
                                        </h5>
                                        <h5>
                                            Credit Amount:  <span class="text-success">+ ${{number_format($completedAmount, 2)}}</span>
                                        </h5>
                                        <h5>
                                            Debit Amount:  <span class="text-danger">- ${{number_format($refundAmount, 2)}}</span>
                                        </h5>
                                        <h5>
                                            Total Amount:  <span class="{{ $totalAmount > 0 ? 'text-success' : 'text-danger' }}">${{number_format($totalAmount, 2)}}</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="media d-flex justify-content-end">
                                <div class="me-4">
                                    <i class="mdi mdi-account-circle text-primary h1"></i>
                                </div>
                                <div class="media-body">
                                    <div class="text-muted" style="max-width: 250px;">
                                        <h5>{{ $buyer->company_name }}</h5>
                                        <p class="mb-1">{{ $buyer->contact_person_name }}</p>
                                        <p class="mb-1"><i class="fa fa-phone"></i> {{ $buyer->company_number }}</p>
                                        <p class="mb-1"><i class="fa fa-building"></i> {{ $buyer->address }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Number</th>
                                        <th>Amount</th>
                                        <th>Order Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
            var table;
            var url = '{!! $data_table_link !!}';

            var columns = [
                { data: "id", name: "id" },
                { data: "order.id", name: "order.id" },
                { data: 'order.sell_amount', name: 'order.sell_amount' },
                { data: 'current_status', name: 'current_status' },
                { data: 'created_at', name: 'created_at', searchable: false },
            ];
            createDataTable(url, columns);
         });
    </script>

    {{-- <script type="text/javascript">
        $(document).ready( function () {
            $(function () {
                var table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,

                    ajax: {
                        url: '{!! $data_table_link !!}',
                        method: 'post'
                    },
                    columns: [
                        { data: "id", name: "id" },
                        { data: 'order_items', name: 'order_items' },
                        { data: 'sell_amount', name: 'sell_amount' },
                        { data: 'order_status', name: 'order_status' },
                        { data: 'created_at', name: 'created_at' },
                    ],

                    // "footerCallback": function ( row, data, start, end, display ) {
                    //     var api = this.api(), data;

                    //     // Remove the formatting to get integer data for summation
                    //     var intVal = function ( i ) {
                    //         return typeof i == 'string' ?
                    //             i.replace(/[\$,]/g, '')*1 :
                    //             typeof i == 'number' ?
                    //                 i : 0;
                    //     };

                    //     // Total over all pages
                    //     total = api
                    //         .column( 2 )
                    //         .data()
                    //         .reduce( function (a, b) {
                    //             return intVal(a) + intVal(b);
                    //         }, 0 );

                    //         // Total over this page
                    //     pageTotal = api
                    //         .column( 2, { page: 'current'} )
                    //         .data()
                    //         .reduce( function (a, b) {
                    //             return intVal(a) + intVal(b);
                    //         }, 0 );


                    //     // Total filtered rows on the selected column (code part added)
                    //     var sumCol4Filtered = display.map(el => data[el][2]).reduce((a, b) => intVal(a) + intVal(b), 0 );

                    //     // Update footer
                    //     $( api.column( 2 ).footer() ).html(
                    //         'Total Amount $'+pageTotal +' <br>(Cancle Order Amount $'+ total +') <br> (Return Order Amount $' + sumCol4Filtered +')'
                    //     );
                    // }
                });
            });
        });

    </script> --}}
@endsection
