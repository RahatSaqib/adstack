@extends($activeTemplate.'layouts.publisher.master')
@section('content')
<div class="row gy-4">
    <!-- < data table -->
    <div class="col-xl-12 col-lg-12 pb-30">
        <div class="mb-30 float-right">
            @if($is_adult == 0)
            <form method="get" action="{{ route('publisher.advertises', '1') }}">
                @csrf
                <button type="submit" class="btn btn--secondary addThirdPartyModal"><i class="fas fa-eye"></i>
                    @lang('Show Adult Script')
                </button>
            </form>
            @else
            <form method="get" action="{{ route('publisher.advertises', '0') }}">
                @csrf
                <button type="submit" class="btn btn--primary addThirdPartyModal"><i class="fas fa-lock"></i>
                    @lang('Hide Adult Script')
                </button>
            </form>
            @endif
        </div>


        <div class="card-wrap">
            <table class="table table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('Ad Name')</th>
                        <th>@lang('Ad Type')</th>
                        <th>@lang('Ad Width')</th>
                        <th>@lang('Ad Height')</th>
                        <th>@lang('Script')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ads as $ad)
                    <tr>
                        <td data-label="@lang('Ad Name')">{{__($ad->ad_name) }}</td>
                        <td data-label="@lang('Ad Type')"><span class="badge badge--base">{{__($ad->type) }}</span></td>
                        <td data-label="@lang('Ad Width')">
                            @if(__($ad->width))
                                {{__($ad->width)}} @lang('px')
                            @else
                                N/A
                            @endif
                        </td>
                        <td data-label="@lang('Ad Height')">
                            @if(__($ad->height))
                                    {{__($ad->height)}} @lang('px')
                            @else
                                    N/A
                            @endif
                        </td>
                        <td data-label="@lang('Script')">
                            <button type="button" class="btn btn-sm btn--primary viewScript" data-id="{{$ad->id}}" data-name="{{$ad->ad_name}}" data-type="{{$ad->type}}" data-third-party-script="{{$ad->third_party_script}}" data-head-script="{{$ad->head_script}}" data-is-third-party="{{$ad->is_third_party}}" data-slug="{{$ad->slug}}" data-status="{{$ad->status}}" data-publisher="{{ Crypt::encryptString(Auth::guard('publisher')->user()->id) }}" data-url="{{url('/')}}">
                                < Get Script>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td data-label="@lang('Advertisement Table')" class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="viewScriptsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Scripts')</h5>
                        <button type="button" class="close btn btn--primary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_data">
                    </div>

                </div>
            </div>
        </div>
    </div>
    @if ($ads->hasPages())
    <div class="d-flex justify-content-end">
        {{ paginateLinks($ads) }}
    </div>
    @endif
</div>
@endsection

@push('script')

<script>
    function copyToClipboard(element, id) {
        'use strict'
        let prefix = element.includes('Head') ? 'h' : 't'
        $(`.${prefix}${id}`).removeClass('d-none');
        var $temp = $(element);
        $temp.select();
        document.execCommand("copy");

        $(`.${prefix}${id}`).addClass('copy-toast').toast('show');

        setTimeout(function() {
            $(`.${prefix}${id}`).removeClass('copy-toast').addClass('d-none');
        }, 1000);
    }

    var modal = $('#viewScriptsModal');
    $('.viewScript').on('click', function() {
        var modal_body = $('#modal_data');
        modal_body.empty()

        var name = $(this).data('name');
        var type = $(this).data('type')
        var isThirdParty = $(this).data('is-third-party')
        var script = $(this).data('third-party-script')
        var publisher = $(this).data('publisher')
        var head = $(this).data('head-script')
        var slug = $(this).data('slug')
        var status = $(this).data('status')
        var id = $(this).data('id')
        var url = $(this).data('url')

        let html = `
        
                    ${head ? `
                        <div class="row">
                            <h4>Copy this code inside your < head > tag</h4>
                            <div class="copy-script">
                                <textarea class="ad-text-area form-control" id="advertHeadScript${id}" rows='8' readonly>
                                ${head.trim()}
                                </textarea>
                                <button class="btn btn--sm script-copy-btn copyButton${id}" onclick='copyToClipboard("#advertHeadScript${id}","${id}")'><i class="fas fa-clipboard"></i> @lang('Copy')</button>

                                <div class="copy-toast h${id} d-none">
                                    @lang('Script Copied')!
                                </div>
                            </div>
                        </div>`
                        :
                        ''
                    }

                     <div class="row">
                            <h4>Copy this code anywhere in your html</h4>
                            <div class="copy-script">
                                <textarea class="ad-text-area lead" id="advertScript${id}" rows='8' readonly>
                                ${isThirdParty ? `<div class='MainAdverTiseMentDiv' data-publisher="${publisher}" data-thirdparty="${isThirdParty}" data-id="${id}" data-ad-type="${type}"></div>
                                            ${script.trim()}
                                            
                                            `
                                    :
                                    `<div class='MainAdverTiseMentDiv' data-publisher="${publisher}" data-adsize="${slug}"></div>
                                        `}
                                        <!-- If the below script already inside body tag don't add twice.
	                                    Start -->
                                    ${'<scr' + `ipt class="adScriptClass" src="${url}/assets/ads/ad.js"></scr` + 'ipt>'}
	                                    <!-- end -->

                                </textarea>
                                <button class="btn btn--sm script-copy-btn copyButton${id}" onclick='copyToClipboard("#advertScript${id}","${id}")'><i class="fas fa-clipboard"></i> @lang('Copy')</button>

                                <div class="copy-toast t${id} d-none">
                                    @lang('Script Copied')!
                                </div>
                            </div>
                    </div>
                 `
        modal_body.append(html)

        modal.modal('show')
    })
</script>
@endpush