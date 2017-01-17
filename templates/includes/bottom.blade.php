<div class="modal fade" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="contentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    </div>
  </div>
</div>

<script>
$(function() {
  $('.login').on('click', function (event) {
    memberLogin();
    event.preventDefault();
  });

  $('.logout').on('click', function (event) {
    var href = $(this).attr('href');

    $.ajax({
      url: app_root + '/member/logout',
      method: 'GET'
    })
    .done(function(json) {
      document.location.reload();
    });

    event.preventDefault();
  });

  $('.content').on('click', function (event) {
    $('#contentModal').modal('show');
    blockUI('#contentModal .modal-content');

    var href = $(this).attr('href');

    $.ajax({
      url: href,
      data: {sl: '{{ $sl_auth }}'},
      method: 'GET'
    })
    .done(function(html) {
      $('#contentModal').find('.modal-content').html(html);
      unblockUI('#contentModal .modal-content');
    });

    event.preventDefault();
  });
});

function memberLogin() {
  $('#contentModal').modal('show');
  blockUI('#contentModal .modal-content');

  $.ajax({
    url: app_root + '/member/login',
    data: {sl: '{{ $sl_auth }}'},
    method: 'GET'
  })
  .done(function(html) {
    $('#contentModal').find('.modal-content').html(html);
    unblockUI('#contentModal .modal-content');
  });
}

function memberRegistered() {
  $('#contentModal').modal('hide');
  swal({
    type: 'success',
    title: "{{ trans('global.member_registration_success') }}"
  });
}
</script>