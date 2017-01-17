<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <div class="card-box">
        <h4 class="page-title m-0">{{ trans('global.admin') }} &rsaquo; {{ trans('global.add_new_user') }}</h4>
      </div>
    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/admin/user/new') }}">
      {!! csrf_field() !!}
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.general') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <label for="email">{{ trans('global.name') }}</label>
              <input type="text" class="form-control" name="name" id="name" value="" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="email">{{ trans('global.email_address') }}</label>
              <input type="email" class="form-control" name="email" id="email" value="" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="password">{{ trans('global.password') }}</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
                <div class="input-group-btn add-on">
                  <button class="btn btn-inverse" type="button" id="show_password" data-toggle="tooltip" title="{{ trans('global.show_hide_password') }}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                  <button class="btn btn-inverse" type="button" id="generate_password" data-toggle="tooltip" title="{{ trans('global.generate_password') }}"><i class="fa fa-random" aria-hidden="true"></i></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="mail_login" id="mail_login" type="checkbox" value="1" checked>
                <label for="mail_login"> {{ trans('global.mail_login') }}</label>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>
            <p class="text-muted">{{ trans('global.active_user_desc') }}</p>
          </fieldset>
        </div>

        <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/admin/users" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-primary waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>
      </div>
      <!-- end col -->
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.role') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <?php
                  $roles = Former::select('role')
                    ->class('select2-required form-control')
                    ->name('role')
                    ->forceValue('user')
                    ->options(trans('global.roles'))
                    ->label(trans('global.role'));
 
                  echo $roles;
?>
            </div>
          </fieldset>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.localization') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <?php
                  echo Former::select('language')
                    ->class('select2-required form-control')
                    ->name('language')
                    ->forceValue($reseller->default_language)
                    ->options(\Platform\Controllers\Core\Localization::getLanguagesArray())
                    ->label(trans('global.language'));
                  ?>
            </div>
            <div class="form-group">
              <?php
                  echo Former::select('timezone')
                    ->class('select2-required form-control')
                    ->name('timezone')
                    ->forceValue($reseller->default_timezone)
                    ->options(trans('timezones.timezones'))
                    ->label(trans('global.timezone'));
                  ?>
            </div>
          </fieldset>
        </div>
      </div>
      <!-- end col -->
      
    </form>
  </div>
  <!-- end row --> 
  
</div>
<script>
  $('#show_password').on('click', function()
  {
    if(! $(this).hasClass('active'))
    {
      $(this).addClass('active');
      togglePassword('password', 'form-control', true);
    }
    else
    {
      $(this).removeClass('active');
      togglePassword('password', 'form-control', false);
    }
  });
  
  $('#generate_password').on('click', function()
  {
    $('#password').val(randomString(8));
  });    
</script>