<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Validate;
use App\Models\Applicant;
use App\Models\Reclite;
use Illuminate\Pagination\Paginator;

class Search
{
    // 検索
    public static function search($request, $per)
    {
        $request = Validate::applicant_search_validation($request);

        $permission_list = checkPermission::check_permission();
        $query = Applicant::query()->with(['department', 'user']);
        if (isset($permission_list)) {
            $query->whereIn('department_id', $permission_list);
        }

        if($request->name !== null) {
            $search_name = $request->name;
            $query->where('name', 'LIKE', "%{$search_name}%");
        }

        if($request->status !== null) {
            $search_status = $request->status;
            $query->where('status', $search_status);
        }

        if($request->department !== null) {
            $search_department = $request->department;
            $query->where('department_id', $search_department);
        }

        if($request->handler !== null) {
            $search_handler = $request->handler;
            $query->where('user_id', $search_handler);
        }

        if(!empty($request->date_from)) {
            $search_date_from = $request->date_from;
            $query->where('updated_at', '>=', $search_date_from);
        }

        if(!empty($request->date_to)) {
            $search_date_to = $request->date_to;
            $query->where('updated_at', '<=', $search_date_to);
        }

        if($request->keyword !== null) {
            $search_keyword = $request->keyword;
            $query->where('memo', 'LIKE', "%{$search_keyword}%");
        }

        return [$query->get(), $query->sortable()->orderByDesc('updated_at')->paginate($per)];
    }

    public static function search_reclite($request, $per)
    {
        $query = Reclite::query()->with(['department', 'user']);

        if($request->name !== null) {
            $search_name = $request->name;
            $query->where('name', 'LIKE', "%{$search_name}%");
        }

        if($request->status !== null) {
            $search_status = $request->status;
            $query->where('status', $search_status);
        }

        if($request->department !== null) {
            $search_department = $request->department;
            $query->where('department_id', $search_department);
        }

        if($request->handler !== null) {
            $search_handler = $request->handler;
            $query->where('user_id', $search_handler);
        }

        if(!empty($request->date_from)) {
            $search_date_from = $request->date_from;
            $query->where('updated_at', '>=', $search_date_from);
        }

        if(!empty($request->date_to)) {
            $search_date_to = $request->date_to;
            $query->where('updated_at', '<=', $search_date_to);
        }
        
        return $query->sortable()->orderByDesc('updated_at')->paginate($per);
    }
}