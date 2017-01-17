<div class="container"> 

  <div class="row m-t">
    <div class="col-sm-12">
      <div class="card-box">
        <h4 class="page-title m-0">{{ trans('global.create_new_coupon') }}</h4>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card-box">

<?php
$count = 1;
foreach($templates as $template) {

  $url = url('c/?state=preview&tpl=' . $template['basename']);
?>
  <a href="javascript:void(0);" class="preview-container" data-basename="{{ $template['basename'] }}" id="container{{ $count }}">
    <iframe src="{{ $url }}" id="frame{{ $count }}" class="preview_frame" frameborder="0" style=""></iframe>
  </a>
<?php
  $count++;
}
?>
<br style="clear: both">

      </div>
    </div>
  </div>
</div>
<script>

$('.preview-container').on('click', function() {
  blockUI();

  var basename = $(this).attr('data-basename');

  var jqxhr = $.ajax({
    url: "{{ url('platform/coupon/new') }}",
    data: {
      basename: basename,
      _token: '{{ csrf_token() }}'
    },
    method: 'POST'
  })
  .done(function(data) {
    if(data.type == 'success')
    {
      document.location = data.redir;
    }
  })
  .fail(function() {
    console.log('error');
  })
  .always(function() {
    unblockUI();
  });
});

blockUI('.preview-container');

$(window).resize(resizeEditFrame);

function resizeEditFrame() {
  $('.preview_frame').each(function() {
    var frame_height = parseInt($(this).contents().find('html').height());
    var frame_width = parseInt($(this).contents().find('html').width());

    $(this).height(frame_height);

    $(this).parent().height(frame_height / 2);
    $(this).parent().width(frame_width / 2);
  });
}

<?php
$count = 1;
foreach($templates as $template) {
?>
$('#frame{{ $count }}').load(function() {
  resizeEditFrame();
  unblockUI('#container{{ $count }}');
});
<?php
  $count++;
}
?>
</script> 
<style type="text/css">
.preview-container:hover {
  border: 2px solid #3bafda;
  transition: border-color 0.5s ease;  
}
.preview-container {
  padding: 5px;
  border: 2px solid transparent;
  overflow: hidden;
  display: block;
  width:160px;
  height:220px;
  float: left;
}
.preview_frame {
  pointer-events: none;
  width:320px;
  -ms-zoom: 0.50;
  -moz-transform: scale(0.50);
  -moz-transform-origin: 0 0;
  -o-transform: scale(0.50);
  -o-transform-origin: 0 0;
  -webkit-transform: scale(0.50);
  -webkit-transform-origin: 0 0;
}
</style>