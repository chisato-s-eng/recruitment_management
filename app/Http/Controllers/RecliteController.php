<?php

namespace App\Http\Controllers;

use App\Services\Search;
use App\Services\Validate;
use App\Services\checkPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Reclite;
use App\Models\Department;
use App\Models\User;
use App\Enums\RecliteStatus;

class RecliteController extends Controller
{
    //
    public function get_reclites(Request $request)
    {
        $request->per ? $per = (int)$request->per : $per=20;
        $departments = Department::all();
        $handlers = User::all();
        $status_list = RecliteStatus::asSelectArray();

        if($request->hasAny(['name', 'status', 'department', 'handler', 'date_from', 'date_to'])) {
            $request = Validate::reclite_search_validation($request);
            $reclites = Search::search_reclite($request, $per);
            $request->flashOnly(['name', 'status', 'department', 'handler', 'date_from', 'date_to']);
        } else {
            $reclites = Reclite::query()->with(['department', 'user'])->sortable()->orderByDesc('updated_at')->paginate($per);
        }
        $reclites->withPath('reclite')->appends(['per' => $per]);
        
        return view('reclite.index', [
            'departments' => $departments,
            'handlers' => $handlers,
            'reclites' => $reclites,
            'per' => $per,
            'status_list' => $status_list,
        ]);
    }

    public function new_reclite()
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('reclite.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }
        $departments = Department::all();
        $handlers = User::all();
        $status_list = RecliteStatus::asSelectArray();
        return view('reclite.new', [
            'departments' => $departments,
            'handlers' => $handlers,
            'status_list' => $status_list,
        ]);
    }

    public function insert_reclite(Request $request)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('reclite.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $request = Validate::reclite_validation($request);

        $reclite = Reclite::create([
            'name' => $request->name,
            'department_id' => $request->department,
            'user_id' => $request->handler,
            'status' => $request->status,
            'memo' => $request->memo
        ]);

        return redirect(route('reclite.list'))->with('success', '求人情報を登録しました。');
    }

    public function get_reclite_info($id)
    {
        $reclite = Reclite::with(['department', 'user'])->where('id', $id)->first();
        $departments = Department::all();
        $handlers = User::all();

        return view('reclite.info', [
            'reclite' => $reclite,
            'departments' => $departments,
            'handlers' => $handlers
        ]);
    }

    public function edit_reclite($id)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('reclite.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $reclite = Reclite::with(['department', 'user'])->where('id', $id)->first();
        $departments = Department::all();
        $handlers = User::all();
        $status_list = RecliteStatus::asSelectArray();

        return view('reclite.edit', [
            'reclite' => $reclite,
            'departments' => $departments,
            'handlers' => $handlers,
            'status_list' => $status_list,
        ]);
    }

    public function update_reclite($id, Request $request)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            redirect(route('reclite.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }
        $request = Validate::reclite_validation($request);

        $reclite = Reclite::where([
            'id' => $id,
            'name' => $request->name,
            'department_id' => $request->department,
            'user_id' => $request->handler,
            'status' => $request->status,
            'memo' => $request->memo
        ])->first();

        if (!$reclite) {
            $update_reclite = Reclite::where('id', $id)->first();
            $update_reclite->update([
                'name' => $request->name,
                'department_id' => $request->department,
                'user_id' => $request->handler,
                'status' => $request->status,
                'memo' => $request->memo
            ]);
        }else{
            return redirect(route('reclite.info', ['id' => $id]))->withErrors(['error' => '更新する情報がありませんでした。']);
        }

        return redirect(route('reclite.info', ['id' => $id]))->with('success', '求人情報を更新しました。');
    }

    public function delete_reclite($id)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            redirect(route('reclite.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $delete = Reclite::where('id', $id)->first();
        if (!$delete) {
            return redirect(route('reclite.list'))->withErrors(['exitError' => $id . 'は存在しないか既に削除されています。']);
        } else {
            $delete->delete();
        }

        return redirect(route('reclite.list'))->with('success', '求人情報を削除しました。');
    }

}
