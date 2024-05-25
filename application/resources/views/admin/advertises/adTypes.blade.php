@extends('admin.layouts.app')
@section('panel')

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Ad Name')</th>
                                <th>@lang('Ad Type')</th>
                                <th>@lang('Width')</th>
                                <th>@lang('Height')</th>
                                <th>@lang('Ad Slug')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adTypes as $type)
                            <tr>
                                <td data-label="@lang('Ad Name')"> {{$type->ad_name}}</td>
                                <td data-label="@lang('Ad Type')"><span
                                        class="text--small badge font-weight-normal badge--warning">{{$type->type}}</span>
                                </td>
                                <td data-label="@lang('Width')">{{ $type->width }}@lang('px')</td>
                                <td data-label="@lang('Height')">{{ $type->height }}@lang('px')</td>
                                <td data-label="@lang('Ad Slug')">{{ $type->slug }}@lang('px')</td>
                                <td data-label="@lang('Status')"><span
                                        class="text--small badge font-weight-normal {{ $type->status ==1 ?'badge--success':'badge--warning'}}">{{ $type->status == 1 ? 'Active':'Deactive' }}</span>
                                </td>
                                <td data-label="Action">
                                    <button type="button" class="btn btn-sm btn--primary edit"
                                            data-id="{{$type->id}}"
                                            data-name="{{$type->ad_name}}"
                                            data-type="{{$type->type}}"
                                            data-tp_cost="{{$type->tp_cost}}"
                                            data-width="{{$type->width}}"
                                            data-height="{{$type->height}}"
                                            data-slug="{{$type->slug}}"
                                            data-status="{{$type->status}}"
                                            data-isthirdparty="{{$type->is_third_party}}"
                                            data-impression="{{$type->is_impression}}"
                                            data-click="{{$type->is_click}}"
                                            data-adult="{{$type->is_adult}}"
                                            data-head="{{$type->head_script}}"
                                            data-script="{{$type->third_party_script}}">
                                        <i class="las la-pen text--shadow"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($adTypes->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($adTypes) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>



    <!--add modal-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.advertise.ads.adType.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Add new Type')</h5>
                        <button type="button" class="close btn btn-outline--danger" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label  for="name"> @lang('Ad Name'):</label>
                                <input type="text" class="form-control" name="ad_name" value="{{ old('ad_name') }}" placeholder="@lang('Ad Name')" required>
                            </div>

                            <div class="form-group">
                                <label for="type"> @lang('Type'):</label>
                                <input type="text" class="form-control" placeholder="@lang('Type')"
                                        name="type" value="image" value="{{ old('type') }}" required readonly>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="width">@lang('Width'): (<span class="text-danger">@lang('px')</span>)</label>
                                    <input type="number" class="form-control" placeholder="@lang('width')"
                                            name="width" id="width" value="{{ old('width') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="height"> @lang('Height'): (<span class="text-danger">@lang('px')</span>)</label>
                                    <input type="number" class="form-control" placeholder="@lang('Height')"
                                            name="height" id="height" value="{{ old('height') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="slug">@lang('Slug'): (<span class="text-danger">@lang('px')</span>)</label>
                                <input class="form-control" type="text" placeholder="@lang('Slug')"
                                id="slug" name="slug" value="" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!--edit modal-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.advertise.ads.adType.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Update')</h5>
                        <button type="button" class="close btn btn-outline--danger" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label  for="ad_name"> @lang('Ad Name'):</label>
                                <input type="text" class="form-control" name="ad_name" value="{{ old('ad_name') }}" placeholder="@lang('Ad Name')" required>
                            </div>

                            <div class="form-group">
                                <label for="type"> @lang('Type'):</label>
                                <input type="text" class="form-control" placeholder="@lang('Type')"
                                        name="type" value="image" value="{{ old('type') }}" required readonly>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="width">@lang('Width'): (<span class="text-danger">@lang('px')</span>)</label>
                                    <input type="number" class="form-control" placeholder="@lang('width')"
                                            name="width" id="widthU" value="{{ old('width') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="height"> @lang('Height'): (<span class="text-danger">@lang('px')</span>)</label>
                                    <input type="number" class="form-control" placeholder="@lang('Height')"
                                            name="height" id="heightU" value="{{ old('height') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="slug">@lang('Slug'): (<span class="text-danger">@lang('px')</span>)</label>
                                <input class="form-control" type="text" placeholder="@lang('Slug')"
                                id="slugU" name="slug" value="" required readonly>
                            </div>

                            <div class="form-group">
                                <label> @lang('Status')</label>
                                <label class="switch m-0" for="statuss">
                                    <input type="checkbox" class="toggle-switch" name="status" id="statuss">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


        <!--add modal-->
        <div class="modal fade" id="addThirdPartyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.advertise.ads.thirdPartyadType.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Add new Type')</h5>
                        <button type="button" class="close btn btn-outline--danger" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label  for="name"> @lang('Ad Name'):</label>
                                <input type="text" class="form-control" name="ad_name" value="{{ old('ad_name') }}" placeholder="@lang('Ad Name')" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Impression') </label>
                                <label class="switch m-0" for="ad-impression">
                                    <input type="checkbox" class="toggle-switch" name="impression" id="ad-impression">
                                    <span class="slider round"></span>
                                </label>
                                <label>@lang('Click') </label>
                                <label class="switch m-0" for="ad-click">
                                    <input type="checkbox" class="toggle-switch" name="click" id="ad-click">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>@lang('Adult') </label>
                                <label class="switch m-0" for="ad-adult">
                                    <input type="checkbox" class="toggle-switch" name="adult" id="ad-adult">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="tp_cost"> @lang('Third Party Cost'):</label>
                                <select name="tp_cost" class="form-control" required>
                                    @foreach($general->tpCostData as $data)
                                        <option value="{{$data->id}}">{{$data->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type"> @lang('Type'):</label>
                                <select name="type" class="form-control" required>
                                    <option value="adcash">Adcash All</option>
                                    <option value="adsterra-banner">Adsterra Banner</option>
                                    <option value="adsterra-social">Adsterra Social</option>
                                    <option value="adcash-inpage">Adcash In-page Push</option>
                                    <option value="adcash-vignette">Adcash Vignette</option>
                                    <option value="google">Google</option>
                                    <option value="monetag-vignette">Monetag Vignette</option>
                                    <option value="monetag-inpage">Monetag In-Page Push</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="head">@lang('Head Tag'):</label>
                                <textarea class="form-control" type="text" placeholder="@lang('Head')" rows='3'
                                id="head" name="head" value=""></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="script">@lang('Script'):</label>
                                <textarea class="form-control" type="text" placeholder="@lang('Script')" rows='8'
                                id="script" name="script" value=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!--edit modal-->
    <div class="modal fade" id="editThirdPartyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.advertise.ads.thirdPartyadType.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Update')</h5>
                        <button type="button" class="close btn btn-outline--danger" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label  for="ad_name"> @lang('Ad Name'):</label>
                                <input type="text" class="form-control" name="ad_name" value="{{ old('ad_name') }}" placeholder="@lang('Ad Name')" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Impression') </label>
                                <label class="switch m-0" for="impression">
                                    <input type="checkbox" class="toggle-switch" name="impression" id="impression">
                                    <span class="slider round"></span>
                                </label>
                                <label>@lang('Click') </label>
                                <label class="switch m-0" for="click">
                                    <input type="checkbox" class="toggle-switch" name="click" id="click">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>@lang('Adult') </label>
                                <label class="switch m-0" for="adult">
                                    <input type="checkbox" class="toggle-switch" name="adult" id="adult">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="tp_cost"> @lang('Third Party Cost'):</label>
                                <select name="tp_cost" class="form-control" required>
                                    @foreach($general->tpCostData as $data)
                                        <option value="{{$data->id}}">{{$data->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type"> @lang('Type'):</label>
                                <select name="type" class="form-control" value="{{old('type')}}" required>
                                    <option value="adcash">Adcash All</option>
                                    <option value="adsterra-banner">Adsterra Banner</option>
                                    <option value="adsterra-social">Adsterra Social</option>
                                    <option value="adcash-inpage">Adcash In-page Push</option>
                                    <option value="adcash-vignette">Adcash Vignette</option>
                                    <option value="google">Google</option>
                                    <option value="monetag-vignette">Monetag Vignette</option>
                                    <option value="monetag-inpage">Monetag In-Page Push</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="head">@lang('Head Tag'):</label>
                                <textarea class="form-control" type="text" placeholder="@lang('Head')" rows='3'
                                id="head" name="head" value="{{ old('head') }}"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="script">@lang('Script'):</label>
                                <textarea class="form-control" type="text" placeholder="@lang('Script')" rows='8'
                                id="script" name="script" value="{{ old('script') }}"></textarea>
                            </div>

                            <div class="form-group">
                                <label> @lang('Status')</label>
                                <label class="switch m-0" for="statuss">
                                    <input type="checkbox" class="toggle-switch" name="status" id="statuss">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn--primary addModal" data-toggle="modal"><i
            class="fas fa-plus"></i>
        @lang('Add new')
    </button>
@endpush
@push('breadcrumb-plugins')
    <button type="button" class="btn btn--primary addThirdPartyModal" data-toggle="modal"><i
            class="fas fa-plus"></i>
        @lang('Add third party')
    </button>
@endpush


@push('script')
    <script>
        'use strict';


        $('.addModal').on('click', function() {
            $('#addModal').modal('show');
        });
        $('.addThirdPartyModal').on('click', function() {
            $('#addThirdPartyModal').modal('show');
        });

        var input, input2;
        $('#width').on('keyup', function () {
            input = $(this).val();
            $('#slug').val(input);
            if (input == '') {
                $('#slug').val('');
            }
        });
        $('#height').on('keyup', function () {
            input2 = $(this).val();
            $('#slug').val(input + 'x' + input2);
            if (input2 == '') {
                $('#slug').val('');
            }
        })

        // update
        var input3, input4;
        $('#widthU').on('keyup', function () {
            input3 = $(this).val();
            $('#slugU').val(input3);
            if (input == '') {
                $('#slugU').val('');
            }
        });
        $('#heightU').on('keyup', function () {
            input4 = $(this).val();
            $('#slugU').val(input3 + 'x' + input4);
            if (input4 == '') {
                $('#slugU').val('');
            }
        })

        var modal = $('#editModal');
        var thirdparty = $('#editThirdPartyModal');

        $('.edit').on('click', function () {
            var name = $(this).data('name');
            var type = $(this).data('type')
            var tp_cost = $(this).data('tp_cost')
            var width = $(this).data('width')
            var height = $(this).data('height')
            var slug = $(this).data('slug')
            var status = $(this).data('status')
            var isThirdParty = $(this).data('isthirdparty')
            var id = $(this).data('id')
            var head = $(this).data('head')
            var script = $(this).data('script')
            var impression = $(this).data('impression')
            var click = $(this).data('click')
            var adult = $(this).data('adult')
            
            if(isThirdParty == 1){
                thirdparty.find('input[name=id]').val(id)
                thirdparty.find('input[name=ad_name]').val(name)
                thirdparty.find('select[name=type]').val(type)
                thirdparty.find('select[name=tp_cost]').val(tp_cost)
                thirdparty.find('textarea[name=head]').val(head)
                thirdparty.find('textarea[name=script]').val(script)
                thirdparty.find('input[name=impression]').attr('checked', impression ? 'checked' : false)
                thirdparty.find('input[name=click]').attr('checked', click ? 'checked' : false)
                thirdparty.find('input[name=status]').attr('checked', status ? 'checked' : false)
                thirdparty.find('input[name=adult]').attr('checked', adult ? 'checked' : false)
                
                thirdparty.modal('show')
            }
            else {
                modal.find('input[name=id]').val(id)
                modal.find('input[name=ad_name]').val(name)
                modal.find('input[name=type]').val(type)
                modal.find('input[name=status]').attr('checked', status ? 'checked' : false)
                modal.find('input[name=width]').val(width)
                modal.find('input[name=height]').val(height)
                modal.find('input[name=slug]').val(slug)
                modal.modal('show')
            }
        })
    </script>

@endpush


