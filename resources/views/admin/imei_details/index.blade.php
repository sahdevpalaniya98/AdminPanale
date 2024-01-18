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
                    <form action="{{ route('admin.imei_details.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="company name">IMEI Number<span class="text text-danger h5"> *</span></label>
                            <input type="text" class="form-control" name="imei_number" id="imei_number" maxlength="150" placeholder="Enter IMEI Number">
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Imei No.</th>
                                        <th>Imei Details</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody />
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
            var url = '{!! route('admin.imei_details.data') !!}';

            var columns = [
                { data: "id", name: "id" },
                { data: 'imei', name: 'imei' },
                { data: 'details', name: 'details' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', sortable: false}
            ];
            createDataTable(url, columns);

            $("#addfrm").validate({
                ignore: [],
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                rules: {
                    imei_number: {
                        required: true
                    },
                },
                messages: {
                    imei_number: {
                        required: "The imei number field is required."
                    },
                },
                submitHandler: function(form) {
                    var form_data = $(form).serialize();
                    $.when(ajax_request($(form).attr('action'),form_data))
                    .done(function(response){
                        if(response.success == true){
                            $(element_model).modal('hide');
                            swal_success('Saved!',response.message);
                            reset_validation(form);
                            setTimeout(function() {
                                reload_table(table);
                            },1000);
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
            });
        });
    </script>
@endsection
