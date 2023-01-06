<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Applicant;
use App\Models\File;
use Illuminate\Pagination\Paginator;

class Validate
{
  public static function applicant_validation($request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'address' => 'nullable|string|max:255',
      'email' => 'nullable|email',
      'mobile_phone' => 'nullable|numeric',
      'home_phone' => 'nullable|numeric',
      'department' => 'required|exists:App\Models\Department,id',
      'reclite' => 'required|exists:App\Models\Reclite,id',
      'handler' => 'required|exists:App\Models\User,id',
      'status' => 'required|integer|between:0,8',
      'file.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx'
    ]);

    return $request;
  }

  public static function applicant_search_validation($request)
  {
    $request->validate([
      'name' => 'nullable|max:255',
      'status' => 'nullable',
      'department' => 'nullable',
      'handler' => 'nullable',
      'date_from' => 'nullable|date',
      'date_to' => 'nullable|date',
      'keyword' => 'nullable|max:255'
    ]);

    return $request;
  }

  public static function reclite_search_validation($request)
  {
    $request->validate([
      'name' => 'nullable|max:255',
      'status' => 'nullable',
      'department' => 'nullable',
      'handler' => 'nullable',
      'date_from' => 'nullable|date',
      'date_to' => 'nullable|date'
    ]);

    return $request;
  }

  public static function reclite_validation($request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'status' => 'required|integer|between:0,2',
      'department' => 'required|exists:App\Models\Department,id',
      'handler' => 'required|exists:App\Models\User,id',
      'memo' => 'nullable|string|max:255'
    ]);

    return $request;
  }
}
