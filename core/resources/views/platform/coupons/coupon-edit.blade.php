<div class="container">
  <div class="row m-t">
    <div class="col-sm-12">
      <div class="card-box">
        <h4 class="page-title m-0">{{ trans('global.edit_coupon') }}</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-9">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/coupon/update') }}">
      <input type="hidden" name="sl" value="{{ $sl }}">
      {!! csrf_field() !!}
      <ul class="nav nav-tabs navtab-custom">
        <li class="active tab"> <a href="#settings" data-toggle="tab" aria-expanded="false">{{ trans('global.settings') }}</a> </li>
        <li class="tab"> <a href="#content" data-toggle="tab" aria-expanded="false">{{ trans('global.content') }}</a> </li>
        <li class="tab"> <a href="#design" data-toggle="tab" aria-expanded="false">{{ trans('global.design') }}</a> </li>
        <li class="tab"> <a href="#email" data-toggle="tab" aria-expanded="false">{{ trans('global.email') }}</a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="settings">
          <div class="row">
            <div class="col-md-6">
              <div class="card-box m-b-0">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.icon') }}</b> <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.icon_coupon_help') }}">&#xE887;</i></h4>
                <div class="form-group" style="margin-bottom:0">
                  <div class="thumb-xl member-thumb m-b-10 center-block">
                    <div style="display:none" class="dropzone-previews" id="dropzone-preview"></div>
                      <img src="{{ $coupon->icon->url('512px') }}" class="img-fluid coupon-icon img-rounded" alt="{{ $coupon->name }}" style="max-width:128px;">
                    </div>

                    <div style="max-width:128px">
                      <button class="btn btn-block btn-warning btn-sm w-sm waves-effect m-t-10 waves-light" id="upload_icon" type="button">{{ trans('global.browse_') }}</button>
                    </div>
<script>
$('#upload_icon').dropzone({ 
  url: '{{ url('platform/coupon/upload-avatar') }}',
  maxFilesize: 3,
  headers: {
    'X-CSRF-Token': '{{ csrf_token() }}'
  },
  previewsContainer: '#dropzone-preview',
  acceptedFiles: 'image/*',
  sending: function(file, xhr, data) {
    data.append('sl', '{{ $sl }}');
    blockUI();
  },
  success : function(file, response) {
    $('.coupon-icon').each(function() {
      $(this).attr('src', response + "?"+ new Date().getTime());
    });
  },
  complete: function() {
    unblockUI();
  },
});

$('#remove_icon').on('click', function() {
  _confirm('{{ url('platform/coupon/delete-avatar') }}', {_token: '{{ csrf_token() }}', sl: '{{ $sl }}'}, 'POST', function(ar1, ar2, json) {
    $('.coupon-icon').each(function() {
      $(this).attr('src', json.src.encoded);
    });
  });
});
</script>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card-box m-b-0">
                <div class="p-20">
                  <div class="form-group">
                    <label for="email">{{ trans('global.name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $coupon->name }}" required autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label class="control-label">{{ trans('global.valid_from') }}</label>
                    <div class="input-daterange input-group date-range">
                      <input type="text" class="form-control" name="valid_from_date" value="{{ $coupon->valid_from_date }}">
                      <span class="input-group-addon bg-inverse b-0 text-white">{{ strtolower(trans('global.to')) }}</span>
                      <input type="text" class="form-control" name="expiration_date" value="{{ $coupon->expiration_date }}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email">{{ trans('global.redeem_code') }}</label>
                    <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.redeem_code_help') }}">&#xE887;</i>
                    <input type="text" class="form-control" name="redeem_code" id="redeem_code" value="{{ $coupon->redeem_code }}" required autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label class="control-label">{{ trans('global.total_amount_of_coupons') }}</label>
                    <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.total_amount_of_coupons_help') }}">&#xE887;</i>
                    <input class="vertical-spin" type="text" value="{{ $coupon->total_amount_of_coupons }}" name="total_amount_of_coupons">
                  </div>
                  <div class="form-group" style="margin-bottom:0">
                    <div class="checkbox checkbox-primary" style="margin-bottom:0">
                      <input name="can_be_redeemed_more_than_once" id="can_be_redeemed_more_than_once" type="checkbox" value="1"{{ ($coupon->can_be_redeemed_more_than_once == 1) ? ' checked' : '' }}>
                      <label for="can_be_redeemed_more_than_once"> {{ trans('global.can_be_redeemed_more_than_once') }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="content">
          <div class="row">
            <div class="col-md-6">
              <div class="card-box m-b-0">
                <div class="p-20">
                  <div class="form-group m-b-0">
                    <label class="control-label">{{ trans('global.navbar') }}</label>
                    <input type="text" class="form-control" name="navbar_text" value="{{ $coupon->navbar_text }}" required autocomplete="off">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="m-t-10">
                        <select class="form-control font-picker" name="navbar_text_font">
                          <?php
foreach (\Config::get('fonts.font_list') as $font_name => $font) {
  $selected = ($font['family'] == $coupon->navbar_text_font) ? ' selected' : '';
  echo '<option value="' . $font['family'] . '"' . $selected . '>' . $font_name . '</option>';
}
?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group m-t-10">
                        <input class="form-control vertical-spin-px" style="text-align: right" type="text" value="{{ $coupon->navbar_text_size }}" name="navbar_text_size">
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-0 m-t-10">
                    <label class="control-label">{{ trans('global.heading') }}</label>
                    <input type="text" class="form-control" name="coupon_title_text" value="{{ $coupon->coupon_title_text }}" required autocomplete="off">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="m-t-10">
                        <select class="form-control font-picker" name="coupon_title_font">
                          <?php
//coupon_title_font
foreach (\Config::get('fonts.font_list') as $font_name => $font) {
  $selected = ($font['family'] == $coupon->coupon_title_font) ? ' selected' : '';
  echo '<option value="' . $font['family'] . '"' . $selected . '>' . $font_name . '</option>';
}
?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group m-t-10">
                        <input class="form-control vertical-spin-px" style="text-align: right" type="text" value="{{ $coupon->coupon_title_size }}" name="coupon_title_size">
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-0 m-t-10">
                    <label class="control-label">{{ trans('global.button') }}</label>
                    <input type="text" class="form-control" name="button_text" value="{{ $coupon->button_text }}" required autocomplete="off">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="m-t-10">
                        <select class="form-control font-picker" name="button_text_font">
                          <?php
foreach (\Config::get('fonts.font_list') as $font_name => $font) {
  $selected = ($font['family'] == $coupon->button_text_font) ? ' selected' : '';
  echo '<option value="' . $font['family'] . '"' . $selected . '>' . $font_name . '</option>';
}
?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group m-t-10  m-b-0">
                        <input class="form-control vertical-spin-px" style="text-align: right" type="text" value="{{ $coupon->button_text_size }}" name="button_text_size">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card-box m-b-0">
                <label class="control-label">{{ trans('global.description') }}</label>

                <textarea class="editor-basic" name="coupon_description_text" style="height:256px">{{ $coupon->coupon_description_text }}</textarea>

                <div class="row m-b-0">
                  <div class="col-md-6">
                    <div class="m-t-10">
                      <select class="form-control font-picker" name="coupon_description_font">
                        <?php
foreach (\Config::get('fonts.font_list') as $font_name => $font) {
$selected = ($font['family'] == $coupon->coupon_description_font) ? ' selected' : '';
echo '<option value="' . $font['family'] . '"' . $selected . '>' . $font_name . '</option>';
}
?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group m-t-10 m-b-0">
                      <input class="form-control vertical-spin-px" style="text-align: right" type="text" value="{{ $coupon->coupon_description_size }}" name="coupon_description_size">
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="design">
          <div class="row">
            <div class="col-md-6">
              <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.navbar') }}</b></h4>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.background_color') }}</label>
                      <div data-color="{{ $coupon->navbar_background_color }}" class="colorpicker-default input-group">
                        <input type="text" name="navbar_background_color" value="{{ $coupon->navbar_background_color }}" class="form-control">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.text_color') }}</label>
                      <div data-color="{{ $coupon->navbar_text_color }}" class="colorpicker-default input-group">
                        <input type="text" name="navbar_text_color" value="{{ $coupon->navbar_text_color }}" class="form-control">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.description') }}</b></h4>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.heading') }}</label>
                      <div data-color="{{ $coupon->coupon_title_color }}" class="colorpicker-default input-group">
                        <input type="text" value="{{ $coupon->coupon_title_color }}" class="form-control" name="coupon_title_color">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.description') }}</label>
                      <div data-color="{{ $coupon->coupon_description_color }}" class="colorpicker-default input-group">
                        <input type="text" value="{{ $coupon->coupon_description_color }}" class="form-control" name="coupon_description_color">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.button') }}</b></h4>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label">{{ trans('global.background_color') }}</label>
                      <div data-color="{{ $coupon->button_background_color }}" class="colorpicker-default input-group">
                        <input type="text" name="button_background_color" value="{{ $coupon->button_background_color }}" class="form-control">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label">{{ trans('global.hover') }}</label>
                      <div data-color="{{ $coupon->button_background_color_hover }}" class="colorpicker-default input-group">
                        <input type="text" name="button_background_color_hover" value="{{ $coupon->button_background_color_hover }}" class="form-control">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.text_color') }}</label>
                      <div data-color="{{ $coupon->button_text_color }}" class="colorpicker-default input-group">
                        <input type="text" name="button_text_color" value="{{ $coupon->button_text_color }}" class="form-control">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="card-box m-b-0">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.other') }}</b></h4>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.qr_color') }}</label>
                      <div data-color="{{ $coupon->qr_color }}" class="colorpicker-default input-group">
                        <input type="text" value="{{ $coupon->qr_color }}" class="form-control" name="qr_color">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <label class="control-label">{{ trans('global.dashed_border') }}</label>
                      <div data-color="{{ $coupon->border_color }}" class="colorpicker-default input-group">
                        <input type="text" value="{{ $coupon->border_color }}" class="form-control" name="border_color">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span> </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-md-6">
              <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.header_images') }}</b></h4>
                <div class="form-group m-b-0">
                  <label for="header_image1">{{ trans('global.main_image') }}</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="header_image1" name="header_image1" autocomplete="off" value="{{ $coupon->header_image1 }}">
                    <div class="input-group-btn add-on">
                      <button type="button" class="btn btn-inverse" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="header_image1" data-preview="header_image1-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                      <button type="button" class="btn btn-inverse disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="header_image1-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                    </div>
                  </div>
                </div>
              </div>


              <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.coupon_background') }}</b></h4>

                <div class="form-group m-t-20">
                  <div class="input-group">
                    <input type="text" class="form-control" id="coupon_background_image" name="coupon_background_image" autocomplete="off" value="{{ $coupon->coupon_background_image }}">
                    <div class="input-group-btn add-on">
                      <button type="button" class="btn btn-inverse" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="coupon_background_image" data-preview="coupon_background_image-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                      <button type="button" class="btn btn-inverse disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="coupon_background_image-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input name="coupon_background_image_repeat" id="coupon_background_image_repeat" type="checkbox" value="1"{{ ($coupon->coupon_background_image_repeat == 1) ? ' checked' : '' }}>
                        <label for="coupon_background_image_repeat"> {{ trans('global.repeat') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <div data-color="{{ $coupon->coupon_background_color }}" class="colorpicker-default input-group">
                        <input type="text" value="{{ $coupon->coupon_background_color }}" class="form-control" name="coupon_background_color">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>


              </div>

              <div class="card-box m-b-0">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.page_background') }}</b></h4>
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control" id="background_image" name="background_image" autocomplete="off" value="{{ $coupon->background_image }}">
                    <div class="input-group-btn add-on">
                      <button type="button" class="btn btn-inverse" data-toggle="tooltip" title="{{ trans('global.browse') }}" data-type="image" data-id="background_image" data-preview="background_image-preview"> <i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                      <button type="button" class="btn btn-inverse disabled" data-toggle="tooltip" title="{{ trans('global.preview') }}" id="background_image-preview"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="checkbox checkbox-primary">
                        <input name="background_image_repeat" id="background_image_repeat" type="checkbox" value="1"{{ ($coupon->background_image_repeat == 1) ? ' checked' : '' }}>
                        <label for="background_image_repeat"> {{ trans('global.repeat') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group m-b-0">
                      <div data-color="{{ $coupon->background_color }}" class="colorpicker-default input-group">
                        <input type="text" value="{{ $coupon->background_color }}" class="form-control" name="background_color">
                        <span class="input-group-btn add-on">
                        <button class="btn btn-inverse" type="button"> <i style="margin-top: 1px;"></i> </button>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>


              </div>

            </div>
          </div>
        </div>
        <div class="tab-pane" id="email">
          <div class="row">
            <div class="col-md-12">
              <div class="card-box m-b-0">
                <h4 class="m-t-0 m-b-20 header-title"><b>{{ trans('global.mail_after_redemption') }}</b></h4>
                <div class="form-group">
                  <label for="navbar_text">{{ trans('global.subject') }}</label>
                  <input type="text" class="form-control" name="redeemed_subject" value="{{ $coupon->redeemed_subject }}" required autocomplete="off">
                </div>
                <textarea name="redeemed_text" class="editor-basic" style="height:320px" required>{{ $coupon->redeemed_text }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
        <div class="panel-body">
          <a href="#/" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
          <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>


          <button type="button" class="btn btn-lg btn-danger waves-effect waves-light w-md delete-coupon pull-right">{{ trans('global.delete_coupon') }}</button>
<?php /*
          <div class="btn-group dropdown">
            <button type="button" class="btn btn-lg btn-warning waves-effect waves-light">{{ trans('global.publish') }}</button>
            <button type="button" class="btn btn-lg btn-warning dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">{{ trans('global.unpublish') }}</a></li>
            </ul>
          </div>
*/ ?>
      </div>
      </div>
    </form>
    </div>
    <div class="col-md-3">
      <ul class="nav nav-tabs navtab-custom">
        <li class="active tab" style="width:50%"> <a href="#preview" data-toggle="tab" aria-expanded="false">{{ trans('global.preview') }}</a> </li>
        <li class="tab" style="width:50%"> <a href="#share" data-toggle="tab" aria-expanded="false">{{ trans('global.share') }}</a> </li>
      </ul>
      <div class="tab-content" style="padding:0">
        <div class="tab-pane active" id="preview">
          <iframe src="{{ $coupon_url . '?state=edit' }}" style="width:100%; height:500px; border:0; display:block;" id="preview_iframe" frameborder="0"></iframe>
        </div>
        <div class="tab-pane" id="share">
          <div style="margin:20px">
            <div class="input-group m-b-20">
              <input type="text" class="form-control" id="coupon_url" readonly value="{{ $coupon_url }}" autocomplete="off">
              <span class="input-group-btn"> <a href="{{ $coupon_url }}" target="_blank" class="btn waves-effect waves-light btn-inverse" style="border-width:2px !important"><i class="fa fa-external-link" aria-hidden="true"></i></a> </span> </div>
            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($coupon_url, 'QRCODE', 10, 10, [255,255,255]) }}" alt="barcode" style="width:100%"> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
blockUI('#preview');

$(window).resize(resizeEditFrame);

function resizeEditFrame() {
  $('#preview_iframe').height( $('#preview_iframe').contents().find('html').height() );
}

$('#preview_iframe').load(function() {
  resizeEditFrame();
  unblockUI('#preview');
});

function couponSaved(responseText) {
  document.getElementById('preview_iframe').contentDocument.location.reload();

  // Loading state
  ladda_button.ladda('stop');

  swal({
    type: 'success',
    timer: 1000,
    showConfirmButton: false,
    title: "{{ trans('global.changes_saved') }}"
  });
}

$('.delete-coupon').on('click', function() {
  swal({
    title: _lang['confirm'],
    type: "warning",
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#DD6B55",
    confirmButtonText: _lang['yes_delete']
  }, 
  function(){
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/coupon/delete') }}",
      data: {sl: "{{ $sl}}",  _token: "{{ csrf_token() }}"},
      method: 'POST'
    })
    .done(function(data) {
      if(data.result == 'success')
      {
        document.location = '#/';
      }
      else
      {
        swal(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });
  });
});
</script> 