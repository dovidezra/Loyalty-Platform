<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;
use App\Notifications\PasswordUpdated;
use App\Notifications\UserCreated;

class UserController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | User Controller
   |--------------------------------------------------------------------------
   |
   | User related logic
   |--------------------------------------------------------------------------
   */

  /**
   * User management
   */
  public function showUsers()
  {
    $users = \App\User::orderBy('name')->get();

    return view('platform.admin.users.users', compact('users'));
  }

  /**
   * New user
   */
  public function showNewUser()
  {
    return view('platform.admin.users.user-new');
  }

  /**
   * Edit user
   */
  public function showEditUser()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $user = \App\User::where('id', $qs['user_id'])->first();

      return view('platform.admin.users.user-edit', [
        'sl' => $sl,
        'user' => $user
      ]);
    }
  }

  /**
   * Upload avatar
   */
  public function postAvatar() {
    $input = array(
      'file' => \Request::file('file'),
      'extension'  => strtolower(\Request::file('file')->getClientOriginalExtension())
    );

    $rules = array(
      'file' => 'mimes:jpeg,gif,png',
      'extension'  => 'required|in:jpg,jpeg,png,gif'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
       echo $validator->messages()->first();
       die();
    } else {
      $sl = request()->input('sl', NULL);
  
      if($sl != NULL) {
        $data = Core\Secure::string2array($sl);
        $user_id = $data['user_id'];
      } else {
        $user_id = \Auth::user()->id;
      }

      $user = \App\User::find($user_id);
      $user->avatar = $input['file'];
      $user->save();

      echo $user->avatar->url('default');
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    $sl = request()->input('sl', NULL);

    if($sl != NULL) {
      $data = Core\Secure::string2array($sl);
      $user_id = $data['user_id'];
    } else {
      $user_id = \Auth::user()->id;
    }

    $user = \App\User::find($user_id);
    $user->avatar = STAPLER_NULL;
    $user->save();

    return response()->json(['src' => $user->getAvatar()]);
  }

  /**
   * Add new user
   */
  public function postNewUser()
  {
    $input = array(
      'timezone' => request()->input('timezone'),
      'language' => request()->input('language'),
      'email' => request()->input('email'),
      'name' => request()->input('name'),
      'password' => request()->input('password'),
      'mail_login' => (bool) request()->input('mail_login', false),
      'active' => (bool) request()->input('active', false),
      'role' =>request()->input('role')
    );

    $rules = array(
      'email' => 'required|email|max:155|unique:users',
      'name' => 'required|max:64',
      'password' => 'required|min:6|max:32'
    );
/*
    if ($input['reseller_id'] > 0 && \Auth::user()->role == 'admin')
    {
      $rules['reseller_id'] = 'required|exists:resellers,id';
    }
*/
    //if (\Auth::user()->role != 'admin')
    //{
      $input['reseller_id'] = Core\Reseller::get()->id;
    //}

    if (\Auth::user()->role == 'reseller')
    {
      if ($input['role'] == 'admin') $input['role'] = 'user';
    }

    // Role validation
    if (\Auth::user()->role == 'reseller')
    {
      $rules['role'] = 'required|in:user,admin,reseller';
    }
    elseif (\Auth::user()->role == 'admin')
    {
      $rules['role'] = 'required|in:user,admin';
    }

    $validator = \Validator::make($input, $rules);

    if($validator->fails())
    {
      $response = array(
        'type' => 'error', 
        'reset' => false, 
        'msg' => $validator->messages()->first()
      );
    }
    else
    {
      $user = new \App\User;

      $user->name = $input['name'];
      $user->email = $input['email'];
      $user->language = $input['language'];
      $user->timezone = $input['timezone'];
      $user->active = $input['active'];
      $user->role = $input['role'];
      if ($input['reseller_id'] > 0) $user->reseller_id = $input['reseller_id'];
      $user->password = bcrypt($input['password']);

      if($input['mail_login'])
      {
        // Send mail with credentials
        $reseller = Core\Reseller::get();

        $user->notify(new UserCreated($input['password'], $reseller->url));

      }

      if($user->save())
      {
        $response = array(
          'type' => 'success',
          'redir' => '#/admin/users'
        );
      }
      else
      {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $user->errors()->first()
        );
      }
    }
    return response()->json($response);
  }

  /**
   * Save user changes
   */
  public function postUser()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      if (config('app.demo') && $qs['user_id'] == 1) {
        return response()->json([
          'type' => 'error',
          'reset' => false, 
          'msg' => "This is disabled in the demo"
        ]);
      }

      $user = \App\User::find($qs['user_id']);

      $input = array(
        'timezone' => request()->input('timezone'),
        'language' => request()->input('language'),
        'email' => request()->input('email'),
        'name' => request()->input('name'),
        'new_password' => request()->input('new_password'),
        'active' => (bool) request()->input('active', false),
        'mail_login' => (bool) request()->input('mail_login', false),
        'role' =>request()->input('role'),
        'reseller_id' =>request()->input('reseller_id', NULL)
      );

      $rules = array(
        'email' => 'required|email|unique:users,email,' . $qs['user_id'],
        'new_password' => 'min:5|max:32',
        'name' => 'required|max:64',
        'timezone' => 'required'
      );

      if ($qs['user_id'] > 1 && $user->reseller != 1)
      {
        //$rules['reseller_id'] = 'exists:resellers,id';
        $rules['role'] = 'required';
      }

      if (\Auth::user()->role == 'reseller')
      {
        if ($input['role'] == 'admin') $input['role'] = 'reseller';
      }

      if (\Auth::user()->role == 'admin')
      {
        if ($input['role'] == 'reseller') $input['role'] = 'admin';
      }

      $validator = \Validator::make($input, $rules);

      if($validator->fails())
      {
        $response = array(
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        );
      }
      else
      {
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->timezone = $input['timezone'];
        $user->language = $input['language'];

        if ($qs['user_id'] > 1 && $user->reseller != 1) 
        {
          $user->active = $input['active'];
          $user->role = $input['role'];

          if (\Auth::user()->role == 'owner')
          {
            if ($input['reseller_id'] == '') $input['reseller_id'] = NULL;
            $user->reseller_id = $input['reseller_id'];
          }
        }
  
        if($input['new_password'] != '')
        {
          $user->password = bcrypt($input['new_password']);

          if($input['mail_login'])
          {
            // Send mail with credentials
            $reseller = Core\Reseller::get();

            $user->notify(new PasswordUpdated($input['new_password']));
          }
        }

        if($user->save())
        {
          $response = array(
            'type' => 'success',
            'reset' => false, 
            'msg' => trans('global.changes_saved')
          );
        }
        else
        {
          $response = array(
            'type' => 'error',
            'reset' => false, 
            'msg' => $user->errors()->first()
          );
        }
      }
      return response()->json($response);
    }
  }

  /**
   * Login as user
   */
  public function getLoginAs($sl)
  {
    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $user = \App\User::find($qs['user_id']);

      if ($user->reseller_id != NULL)
      {
        // Set session to redirect to in case of logout
        $logout = Core\Secure::array2string(['user_id' => \Auth::user()->id]);
        \Session::put('logout', $logout);

        \Auth::loginUsingId($qs['user_id']);

        return redirect('platform');
      }
    }
  }

  /**
   * Delete user
   */
  public function postUserDelete()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $response = array('result' => 'success');

      if (config('app.demo') && $qs['user_id'] == 1) {
        return response()->json([
          'type' => 'error',
          'reset' => false, 
          'msg' => "This is disabled in the demo"
        ]);
      }

      $user = \App\User::where('id', '>',  1)->where('reseller', false)->where('id', '=',  $qs['user_id'])->first();

      if(! empty($user))
      {
        $user = \App\User::where('id', '=',  $qs['user_id'])->forceDelete();

        // Delete user uploads
        $user_dir = public_path() . '/uploads/' . Core\Secure::staticHash($qs['user_id']);
        \File::deleteDirectory($user_dir);
      }
      else
      {
        $response = array('msg' => trans('global.cant_delete_owner'));
      }
    }
    return response()->json($response);
  }

  /**
   * Get user data
   */
  public function getUserData(Request $request)
  {
    $sql_reseller = "1=1";
    $sql_role = "1=1";

    if (\Auth::user()->role != 'admin')
    {
      $reseller_id = Core\Reseller::get()->id;
      $sql_reseller = "reseller_id = " . $reseller_id;
    }

    if (\Auth::user()->role == 'admin')
    {
      //$sql_role = "role <> 'reseller' AND role <> 'owner'";
    }

    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();

    $aColumn = array('email', 'users.name', 'role', 'logins', 'last_login', 'users.created_at', 'users.active');

    if($q != '')
    {
      $count = \App\User::leftJoin('resellers as r', 'r.id', '=', 'reseller_id')
        ->select(array('users.*'))
        ->whereRaw($sql_reseller)->whereRaw($sql_role)
        ->where('parent_id', '=', NULL)
        ->where(function ($query) use($q) {
          $query->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('role', 'like', '%' . $q . '%')
          ->orWhere('users.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = \App\User::orderBy($aColumn[$order_by], $order)
        ->leftJoin('resellers as r', 'r.id', '=', 'reseller_id')
        ->select(array('users.*'))
        ->whereRaw($sql_reseller)->whereRaw($sql_role)
        ->where('parent_id', '=', NULL)
        ->where(function ($query) use($q) {
          $query->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('role', 'like', '%' . $q . '%')
          ->orWhere('users.name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = \App\User::leftJoin('resellers as r', 'r.id', '=', 'reseller_id')->whereRaw($sql_reseller)->whereRaw($sql_role)->where('parent_id', '=', NULL)->select(array('users.*'))->count();
      $oData = \App\User::orderBy($aColumn[$order_by], $order)->leftJoin('resellers as r', 'r.id', '=', 'reseller_id')->whereRaw($sql_reseller)->whereRaw($sql_role)->where('parent_id', '=', NULL)->select(array('users.*'))->take($length)->skip($start)->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row) {
      $expires = ($row->expires == NULL) ? '-' : $row->expires->format('Y-m-d');
      $last_login = ($row->last_login == NULL) ? '' : $row->last_login->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
      $undeletable = ($row->id == 1 || $row->reseller == 1) ? 1 : 0;

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'reseller' => ($row->reseller_name == '') ? '-' : $row->reseller_name,
        'name' => $row->name,
        'email' => $row->email,
        'role' => trans('global.roles.' . $row->role),
        'logins' => $row->logins,
        'last_login' => $last_login,
        'active' => $row->active,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('user_id' => $row->id)),
        'undeletable' => $undeletable
      );
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    echo json_encode($response);
  }
}