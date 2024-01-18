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

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.brand.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                       
                        <div class="mb-3">
                            <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                            <label class="form-label" for="brand name">Brand Name<span class="text text-danger h5">
                                    *</span></label>
                            <input type="text" class="form-control" name="brand_name" id="brand_name"
                                value="{{ $brand->brand_name }}" placeholder="Enter Phone Brand Name">
                            @error('brand_name')
                                <span class="text text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        @php
                        $icon  = (isset($brand->brand_icon) && $brand->brand_icon != '' && \File::exists(public_path('uploads/brands/'.$brand->brand_icon))) ? asset('uploads/brands/'.$brand->brand_icon) : '';
                        @endphp

                        @if($icon)
                            @php
                                $iconExits = 'logo-exist';
                            @endphp
                        @else
                            @php
                                $iconExits = '';
                            @endphp
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Brand Icon <span class="text-danger">*</span></label>
                            <input type="file" class="form-control dropify {{ $iconExits }}" name="brand_icon" id="brand_icon" data-default-file="{{ $icon }}" data-allowed-file-extensions="gif png jpg jpeg" data-max-file-size="5M" data-show-errors="true" data-errors-position="outside" data-show-remove="false">
                            @error('brand_icon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                            
                        <div class="mb-3">
                            <label class="form-label" for="remark ">Remark </label>
                            <textarea type="text" class="form-control" name="remark" id="remark" placeholder="Enter remark ">{{ (isset($brand->remark) && $brand->remark) ? $brand->remark : ""}}</textarea>
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
                            <a href="{{ route('admin.brand.index') }}" class="btn btn-secondary waves-effect cancle">
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
                    expense_name: {
                        required: true
                    },
                   
                },
                messages: {
                    brand_name: {
                        required: "The brand name field is required."
                    },
                    
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
@endsection
