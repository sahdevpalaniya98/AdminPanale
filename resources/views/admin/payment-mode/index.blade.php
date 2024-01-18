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
                                        <th>Payment Name</th>
                                        <th>Remark</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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
            var table;
            var url = '{!! route('admin.payment.mode.data') !!}';

            var columns = [
                { data: "id", name: "id" },
                { data: 'payment_name', name: 'payment_name' },
                { data: 'remark', name: 'remark' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', sortable: false}
            ];
            createDataTable(url, columns);
        });
    </script>
@endsection
