@extends('layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('page-style')
    <style>
        .card-body{
            padding: 0;
        }
    </style>
@endsection
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="brand_id">Search Brand</label>
                            <select class="form-select select2" name="brand_id" id="brand_id">
                                <option value="">Please select brand</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="category_id">Search Category</label>
                            <select class="form-select select2" name="category_id" id="category_id">
                                <option value="">Please select category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="series_id">Search Series</label>
                            <select class="form-select select2" name="series_id" id="series_id">
                                <option value="">Please select series</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="model_id">Search Model</label>
                            <select class="form-select select2" name="model_id" id="model_id">
                                <option value="">Please select model</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="employee_id">Search Employee</label>
                            <select class="form-select select2" name="employee_id" id="employee_id">
                                <option value="">Please select employee</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="sold">Search Phone Avaliblity</label>
                            <select class="form-select select2" name="sold" id="sold">
                                <option value="">Please select</option>
                                <option value="1">Sold</option>
                                <option value="0">Available</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary btn-sm w-md" id="remove"
                                style="margin-top:25px;height: 36px;">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                        <th>Brand Name</th>
                                        <th>Category Name</th>
                                        <th>Series Name</th>
                                        <th>Phone Name</th>
                                        <th>IMEI Number</th>
                                        <th>Serial Number</th>
                                        <th>Branch Name</th>
                                        <th>Purchase Price</th>
                                        <th>Sold Status</th>
                                        <th>Action</th>
                                        <th>Purchase From</th>
                                        <th>Purchase By</th>
                                        <th>Phone Grade</th>
                                        <th>Battery Health</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
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
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function() {
            var table;
            var url = '{!! route('admin.inventory.data') !!}';

            var columns = [{
                    data: "id",
                    name: "id"
                },
                {
                    data: 'brand_id',
                    name: 'brand_id'
                },
                {
                    data: 'category_id',
                    name: 'category_id'
                },
                {
                    data: 'series_id',
                    name: 'series_id'
                },
                {
                    data: 'model_id',
                    name: 'model_id'
                },
                {
                    data: 'imei_number',
                    name: 'imei_number'
                },
                {
                    data: 'serial_number',
                    name: 'serial_number'
                },
                {
                    data: 'branch_id',
                    name: 'branch_id'
                },
                {
                    data: 'purchase_price',
                    name: 'purchase_price'
                },
                {
                    data: 'is_sold',
                    name: 'is_sold'
                },
                {
                    data: 'action',
                    name: 'action',
                    sortable: false
                },
                {
                    data: 'customer_id',
                    name: 'customer_id'
                },
                {
                    data: 'employee_id',
                    name: 'employee_id'
                },
                {
                    data: 'phone_grade_id',
                    name: 'phone_grade_id'
                },
                {
                    data: 'bettry_health',
                    name: 'bettry_health'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ];
            createDataTable(url, columns, ['brand_id', 'category_id', 'series_id', 'model_id', 'employee_id',
                'sold'
            ], [
                'colvis'
            ]);
            $('#brand_id').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('admin.ajax.phone.brand.list') }}",
                    dataType: 'json',
                    delay: 250,
                    method: 'post',
                    data: function(term) {
                        return {
                            search: term.term
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
            });
        });

        $('#category_id').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.category.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term,
                        brand_id: $('#brand_id').val(),
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
        });

        $('#series_id').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.phone.series.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term,
                        category_id: $('#category_id').val(),
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
        });

        $('#employee_id').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.employee.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term
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
        });

        $('.select2').change(function() {
            table.draw();
        });


        $('#model_id').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.phone.model.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term,
                        brand_id: $('#brand_id').val(),
                        series_id: $('#series_id').val(),
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
        });

        $('#remove').click(function() {
            $("#brand_id").val(null).trigger('change');
            $("#category_id").val(null).trigger('change');
            $("#series_id").val(null).trigger('change');
            $("#employee_id").val(null).trigger('change');
            $("#sold").val(null).trigger('change');
            table.draw();
        });
    </script>
@endsection
