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
                    <form action="{{ route('admin.variant.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    @if (isset($variant) && $variant != '')
                                        <input type="hidden" name="variant_id" id="variant_id"
                                            value="{{ $variant->id }}" />
                                    @endif
                                    <label class="form-label" for="type">Variant Type</label>
                                    <select class="form-select" name="type" id="type">
                                        <option value="color"
                                            {{ isset($variant) && $variant->type == 'color' ? 'selected' : '' }}>Color
                                        </option>
                                        <option value="space"
                                            {{ isset($variant) && $variant->type == 'space' ? 'selected' : '' }}>Space
                                        </option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="title">Variant Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ isset($variant) && $variant->title != '' ? $variant->title : old('title') }}"
                                        maxlength="150">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.variant.index') }}" class="btn btn-secondary waves-effect">
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
                    type: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                },
                messages: {
                    type: {
                        required: "The variant type field is required."
                    },
                    title: {
                        required: "The variant title field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
@endsection
