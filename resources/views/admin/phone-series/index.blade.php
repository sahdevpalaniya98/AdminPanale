@extends('layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif



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
                    <div class="col-lg-3 col-6">
                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary btn-sm w-md" id="remove"
                                style="margin-top:30px">Clear</button>
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
                                        <th>Brand Name </th>
                                        <th>Category Name </th>
                                        <th>Series Name</th>
                                        <th>Remark </th>
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
            var url = '{!! route('admin.phone.series.data') !!}';

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
                    data: 'series_name',
                    name: 'series_name'
                },
                {
                    data: 'remark',
                    name: 'remark'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    sortable: false
                }
            ];
            createDataTable(url, columns, ['brand_id','category_id']);


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
        $('#brand_id').change(function() {
            table.draw();
        });

        $('#category_id').change(function() {
            table.draw();
        });

        $('#remove').click(function() {
            $("#brand_id").val(null).trigger('change');
            $("#category_id").val(null).trigger('change');
            table.draw();
        });
    </script>
@endsection
