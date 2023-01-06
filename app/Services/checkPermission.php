<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Applicant;
use App\Models\User;
use App\Models\Permission;

class checkPermission
{
  public static function check_permission() {
    $user = Auth::user();
    if ($user->is_admin === 0) {
      return null;
    }
    $permissions = $user->permissions;
    foreach($permissions as $permission) {
      $permission_list[] = $permission->department_id;
    }
    return $permission_list;
  }

  public static function check_is_admin() {
    $user = Auth::user();
    if ($user->is_admin === 0){
      return true;
    }
    return false;
  }

  public static function filter_applicant($per)
  {
    $permission_list = checkPermission::check_permission();
    $query = Applicant::query()->with(['department', 'user']);
    if (isset($permission_list)) {
      $query->whereIn('department_id', $permission_list);
    }

    return [$query->get(), $query->sortable()->orderByDesc('updated_at')->paginate($per)->onEachSide(1)];
  }
}