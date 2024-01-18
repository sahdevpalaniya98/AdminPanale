@extends('layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('assets/libs/dropify/dist/css/dropify.min.css') }}">
@endsection

@section('breadcrumb')
    @include('layouts.includes.breadcrumb')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.branch.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($branch)
                            <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}" />
                        @endisset
                        <div class="mb-3">
                            <label class="form-label" for="branch_name">Branch Name<span class="text text-danger h5">
                                    *</span></label>
                            <input type="text" class="form-control" name="branch_name" id="branch_name"
                                placeholder="Enter Branch Name" value="{{ (isset($branch) && $branch->name != "" ) ? $branch->name : old('name') }}">
                            @error('branch_name')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="branch_location">Branch Location <span class="text text-danger h5">
                                *</span></label>
                            <textarea type="text" class="form-control" name="branch_location" id="branch_location" placeholder="Enter Branch Location">{{ (isset($branch) && $branch->location != "" ) ? $branch->location : old('location') }}</textarea>
                            @error('branch_location')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                               {{ isset($branch) ? 'update': 'save' }}
                            </button>
                            <a href="{{ route('admin.branch.index') }}" class="btn btn-secondary waves-effect">
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
    <script src="{{ asset('assets/libs/dropify/dist/js/dropify.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
            $('.dropify').dropify();
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
                    branch_name: {
                        required: true
                    },
                    branch_location: {
                        required: true
                    },
                },

                messages: {
                    branch_name: {
                        required: "The branch name field is required."
                    },
                    branch_location: {
                        required: "The branch icon field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
@endsection
