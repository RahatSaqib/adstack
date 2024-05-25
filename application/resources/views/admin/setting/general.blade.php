@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <div class="card-body px-4">
                <form action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label class="required"> @lang('Site Title')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <input class="form-control" type="text" name="site_name" required value="{{$general->site_name}}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label class="required">@lang('Currency')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <input class="form-control" type="text" name="cur_text" required value="{{$general->cur_text}}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label class="required">@lang('Currency Symbol')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <input class="form-control" type="text" name="cur_sym" required value="{{$general->cur_sym}}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Cost Per Impression')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control" name="cpm" type="text" value="{{$general->cpm}}" id="cpm">
                                        <span class="input-group-text">{{__($general->cur_text)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Cost Per Click')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control" name="cpc" type="text" value="{{$general->cpc}}" id="cpc">
                                        <span class="input-group-text">{{__($general->cur_text)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label class="required">@lang('Max Allowed Cost in Same IP')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <input class="form-control" type="number" name="same_ip_limit" required value="{{$general->same_ip_limit}}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label class="required">@lang('Cost Unit')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <input class="form-control" type="number" name="cost_unit" required value="{{$general->cost_unit}}">
                                </div>
                            </div>

                            <!-- <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Cost Impression(Publisher)')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control" name="cpm_pub" type="text" value="{{$general->cpm_pub}}" id="cpm_pub">
                                        <span class="input-group-text">{{__($general->cur_text)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Cost Click(Publisher)')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control" name="cpc_pub" type="text" value="{{$general->cpc_pub}}" id="cpc_pub">
                                        <span class="input-group-text">{{__($general->cur_text)}}</span>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Timezone')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <select class="select2-basic" name="timezone">
                                        @foreach($timezones as $timezone)
                                        <option value="'{{ @$timezone }}'">{{ __($timezone) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Site Base Color')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-text p-0 border-0">
                                            <input type='text' class="form-control colorPicker" value="{{$general->base_color}}" />
                                        </span>
                                        <input type="text" class="form-control colorCode" name="base_color" value="{{ $general->base_color }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Site Secondary Color')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-text p-0 border-0">
                                            <input type='text' class="form-control colorPicker" value="{{$general->secondary_color}}" />
                                        </span>
                                        <input type="text" class="form-control colorCode" name="secondary_color" value="{{ $general->secondary_color }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                    <label> @lang('Visit ipstack.com to get your api key')</label>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="location_api" value="{{$general->location_api}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('User Registration')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="registration" {{
                                    $general->registration ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Email Verification')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="ev" {{ $general->ev ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Email Notification')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="en" {{ $general->en ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Mobile Verification')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="sv" {{ $general->sv ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('SMS Notification')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="sn" {{ $general->sn ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Terms & Condition')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="agree" {{ $general->agree ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Domain')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="domain" {{ $general->domain_approval ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Check Country')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="check_country" {{ $general->check_country ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="form-group col-md-2 col-sm-6 mb-4">
                            <label class="fw-bold">@lang('Check Domain Keywords')</label>
                            <label class="switch m-0">
                                <input type="checkbox" class="toggle-switch" name="check_domain_keyword" {{ $general->check_domain_keyword ?
                                'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col text-end">
                            <button type="submit" class="btn btn--primary btn-global">@lang('Save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body px-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10 ">
                            <div class="card-header text-right">
                                @lang('Third Party Cost'
                                )<button type="button" class="btn btn-sm btn--primary addBtn" style="float:right"><i class="las la-plus"></i>@lang('Add
                                    New')</button>
                            </div>
                            <div class="card-body p-0">
                                @if($general->tpCostData)
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table table--light style--two custom-data-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Name')</th>
                                                <th>@lang('Cost Per Impression')</th>
                                                <th>@lang('Cost Per Click')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($general->tpCostData as $k => $data)
                                            <tr>
                                                <td>{{ __($data->title) }}</td>
                                                <td>{{ __($data->cpm_pub) }}</td>
                                                <td>{{ __($data->cpc_pub) }}</td>
                                                <td>
                                                    <div class="button--group">
                                                        <a title="@lang('Edit')" class="btn btn-sm btn--primary edit" data-id="{{$data->id}}" data-title="{{$data->title}}" data-cpm_pub="{{$data->cpm_pub}}" data-cpc_pub="{{$data->cpc_pub}}"><i class="la la-pen"></i>
                                                        </a>

                                                        <button title="@lang('Delete')" class="btn btn-sm btn--danger deleteBtn" data-id="{{ $data->id }}">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table><!-- table end -->
                                </div>
                                @endif
                            </div>
                        </div><!-- card end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addThirdPartyCostModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Cost')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.setting.tpcost.add')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label> @lang('Title')</label>
                            <input type="text" class="form-control" name="title" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                <label> @lang('Cost Impression(Publisher)')</label>
                            </div>
                            <div class="col-md-9 col-xs-12">
                                <div class="input-group">
                                    <input class="form-control" name="cpm_pub" type="text" id="add_cpm_pub">
                                    <span class="input-group-text">{{__($general->cur_text)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-xs-4 d-flex align-items-center">
                                <label> @lang('Cost Click(Publisher)')</label>
                            </div>
                            <div class="col-md-9 col-xs-12">
                                <div class="input-group">
                                    <input class="form-control" name="cpc_pub" type="text" id="add_cpc_pub">
                                    <span class="input-group-text">{{__($general->cur_text)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary btn-global">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editThirdPartyCostModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Update Cost Detail')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.setting.tpcost.update')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">

                        <div class="form-group">
                            <label> @lang('Title')</label>
                            <input type="text" class="form-control" name="title" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12 d-flex align-items-center">
                                <label> @lang('Cost Impression(Publisher)')</label>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="input-group">
                                    <input class="form-control" name="cpm_pub" type="text" id="add_cpm_pub">
                                    <span class="input-group-text">{{__($general->cur_text)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12 d-flex align-items-center">
                                <label> @lang('Cost Click(Publisher)')</label>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="input-group">
                                    <input class="form-control" name="cpc_pub" type="text" id="add_cpc_pub">
                                    <span class="input-group-text">{{__($general->cur_text)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary btn-global">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteThirdPartyCostModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Third Party Cost Delete Confirmation')</h5>
                    <button type="button" class="close btn btn--danger btn--sm" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.setting.tpcost.delete')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="fw-bold">@lang('delete')</span> <span class="fw-bold withdraw-amount text-success"></span> @lang('this TP Cost') <span class="fw-bold withdraw-user"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('script-lib')
<script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        $('.colorPicker').spectrum({
            color: $(this).data('color'),
            change: function(color) {
                $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
            }
        });

        $('.colorCode').on('input', function() {
            var clr = $(this).val();
            $(this).parents('.input-group').find('.colorPicker').spectrum({
                color: clr,
            });
        });

        $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2();
        $('.select2-basic').select2({
            dropdownParent: $('.card-body')
        });


        $('.deleteBtn').on('click', function() {
            var modal = $('#deleteThirdPartyCostModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');

        });

        $('.addBtn').on('click', function() {
            var modal = $('#addThirdPartyCostModal');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal('show');
        });

        var thirdparty = $('#editThirdPartyCostModal');

        $('.edit').on('click', function() {
            var title = $(this).data('title');
            var cpcPub = $(this).data('cpc_pub')
            var cpmPub = $(this).data('cpm_pub')
            var id = $(this).data('id')
            thirdparty.find('input[name=id]').val(id)
            thirdparty.find('input[name=title]').val(title)
            thirdparty.find('input[name=cpc_pub]').val(cpcPub)
            thirdparty.find('input[name=cpm_pub]').val(cpmPub)
            thirdparty.modal('show')

        })


    })(jQuery);
</script>
@endpush