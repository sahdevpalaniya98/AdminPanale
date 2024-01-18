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
                    <form action="{{ route('admin.category.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="brand_id">Brand<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="brand_id" id="brand_id">
                                        <option value="">Please select brand</option>
                                    </select>
                                    @error('brand_id')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="phone category">Phone Category<span
                                            class="text text-danger h5">
                                            *</span></label>
                                    <input type="text" class="form-control" name="category_name" id="category_name"
                                        placeholder="Enter Category Name">
                                    @error('category_name')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category Icon<span class="text-danger">*</span></label>
                            <input type="file" class="form-control dropify" name="category_icon" id="category_icon"
                                data-allowed-file-extensions="gif png jpg jpeg" data-max-file-size="5M"
                                data-show-errors="true" data-errors-position="outside" data-show-remove="false">
                            @error('category_icon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="remark">Remark</label>
                            <textarea name="remark" class="form-control" id="remark"></textarea>
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
                            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary waves-effect">
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
                    brand_id: {
                        required: true
                    },
                    category_name: {
                        required: true
                    },
                    category_icon: {
                        required: true
                    },
                },

                messages: {
                    brand_id: {
                        required: "The brand field is required."
                    },
                    category_name: {
                        required: "The category name field is required."
                    },
                    category_icon: {
                        required: "The category icon field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
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
    </script>
@endsection
