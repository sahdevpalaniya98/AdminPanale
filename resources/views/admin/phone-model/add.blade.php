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
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.phone.model.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="brand_id">Brand<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="brand_id" id="brand_id">
                                        <option value="">Please select brand</option>
                                        {{-- @if (isset($brands) && count($brands) > 0)
                                            @foreach ($brands as $key => $brand)
                                                <option value="{{ $brand->id }}"
                                                    @if (old('brand_id') == $brand->id) selected="selected" @endif>
                                                    {{ $brand->brand_name }}</option>
                                            @endforeach
                                        @endif --}}
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="category_id">Brand Category<span
                                            class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="category_id" id="category_id">
                                        <option value="">Please select category</option>
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label" for="series_id">Phone Series<span class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="series_id" id="series_id">
                                        <option value="">Please select phone series</option>
                                        {{-- @if (isset($series) && count($series) > 0)
                                            @foreach ($series as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    @if (old('series_id') == $value->id) selected="selected" @endif>
                                                    {{ $value->series_name }}</option>
                                            @endforeach
                                        @endif --}}
                                    </select>
                                    @error('series_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="variant_id">Phone Variant<span
                                            class="text text-danger h5">
                                            *</span></label>
                                    <select class="form-select select2" name="variant_id[]" id="variant_id"
                                        multiple="multiple">
                                        <option value="">Please select phone variant</option>
                                    </select>
                                    @error('variant_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="model name">Model Name<span class="text text-danger h5">
                                            *</span></label>
                                    <input type="text" class="form-control" name="model_name" id="model_name"
                                        placeholder="Enter Phone Model Name">
                                    @error('model_name')
                                        <span class="text text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="remark ">Remark</label>
                            <textarea name="remark" class="form-control" id="remark"></textarea>
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.phone.model.index') }}" class="btn btn-secondary waves-effect">
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
    <script type="text/javascript">
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
                    category_id: {
                        required: true
                    },
                    series_id: {
                        required: true
                    },
                    model_name: {
                        required: true
                    },
                },

                messages: {
                    brand_id: {
                        required: "The brand field is required."
                    },
                    category_id: {
                        required: "The category field is required."
                    },
                    series_id: {
                        required: "The phone series field is required."
                    },
                    model_name: {
                        required: "The model name field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
        $('#brand_id').on('change', function() {
            $("#category_id").val(null).trigger('change');
        })

        $('#category_id').on('change', function() {
            $("#series_id").val(null).trigger('change');
        })

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

        $('#variant_id').select2({
            width: '100%',
            ajax: {
                url: "{{ route('admin.ajax.variant.list') }}",
                dataType: 'json',
                delay: 250,
                method: 'post',
                data: function(term) {
                    return {
                        search: term.term,
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
    </script>
@endsection
