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
                    <form action="{{ route('admin.phone.damage.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                       
                        <div class="mb-3">
                            <input type="hidden" name="phone_damage_id" value="{{ $phone_damage->id }}" />
                            <label class="form-label" for="phone damage">Phone Damage Name<span class="text text-danger h5">
                                    *</span></label>
                            <input type="text" class="form-control" name="damage_name" id="damage_name"
                                value="{{ $phone_damage->damage_name }}" placeholder="Enter Phone Damage Name">
                            @error('damage_name')
                                <span class="text text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                            
                        <div class="mb-3">
                            <label class="form-label" for="note">Note<span class="text text-danger h5">
                                *</span></label>
                            <textarea type="text" class="form-control" name="note" id="note" placeholder="Enter Note ">{{ (isset($phone_damage->note) && $phone_damage->note) ? $phone_damage->note : ""}}</textarea>
                            @error('note')
                                <span class="text text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2 fr-button">
                            <button type="submit" id="submit_data" class="btn btn-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.phone.damage.index') }}" class="btn btn-secondary waves-effect cancle">
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
            $('.js-example-basic-multiple').select2();
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
                    damage_name: {
                        required: true
                    },
                    note: {
                        required: true
                    },
                },
                messages: {
                    damage_name: {
                        required: "The phone damage name field is required."
                    },
                    note: {
                        required: "The phone damage note field is required."
                    },
                },
                submitHandler: function(e) {
                    e.submit()
                }
            });
        });
    </script>
@endsection
