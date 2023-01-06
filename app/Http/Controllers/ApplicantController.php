<?php

namespace App\Http\Controllers;

use App\Services\Search;
use App\Services\checkPermission;
use App\Services\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use App\Models\Applicant;
use App\Models\Department;
use App\Models\Reclite;
use App\Models\User;
use App\Models\File;
use App\Models\Record;
use App\Enums\ApplicantStatus;

class ApplicantController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    //
    public function get_applicants(Request $request)
    {
        $request->per ? $per = (int)$request->per : $per=20;
        $departments = Department::all();
        $handlers = User::all();
        $status_list = ApplicantStatus::asSelectArray();

        if ($request->hasAny(['name', 'status', 'department', 'handler', 'date_from', 'date_to', 'keyword'])) {
            $results = Search::search($request, $per);
            $counts = $results[0];
            $applicants = $results[1]; 
            $request->flashOnly(['name', 'status', 'department', 'handler', 'date_from', 'date_to', 'keyword']);
        } else {
            $results = checkPermission::filter_applicant($per);
            $counts = $results[0];
            $applicants = $results[1];
        }
        $applicants->withPath('applicant')->appends([ 'per' => $per ]);
        return view('applicant.index', [
            'departments' => $departments,
            'handlers' => $handlers,
            'applicants' => $applicants,
            'counts' => $counts,
            'per' => $per,
            'status_list' => $status_list,
        ]);
    }

    public function new_applicant()
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }
        $departments = Department::all();
        $handlers = User::all();
        $reclites = Reclite::where('status', 0)->get();
        $status_list = ApplicantStatus::asSelectArray();
        return view('applicant.new', [
            'departments' => $departments,
            'handlers' => $handlers,
            'reclites' => $reclites,
            'status_list' => $status_list,
        ]);
    }

    public function insert_applicant(Request $request)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }
        
        $request = Validate::applicant_validation($request);

        DB::transaction(function () use($request) {
            $applicant = Applicant::create([
                'name' => $request->name,
                'address' => $request->address,
                'email' => $request->email,
                'mobile_phone' => $request->mobile_phone,
                'home_phone' => $request->home_phone,
                'department_id' => $request->department,
                'reclite_id' => $request->reclite,
                'user_id' => $request->handler,
                'status' => $request->status,
                'memo' => $request->memo
                ]);
            $last_id = $applicant->id;
            if ($request->file !== null) {
                $files = $request->file('file');
                foreach($files as $file) {
                    $filemime = $file->getClientOriginalExtension();
                    $filepath = Str::random(20);
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $file_upload = File::create([
                        'applicant_id' => $last_id,
                        'filename' => $filename,
                        'filepath' => $filepath
                    ]);

                    $path = $file->getPathname();
                    if ($filemime !== "pdf") {
                        exec("export HOME=/tmp;/usr/bin/soffice --headless --convert-to pdf:writer_pdf_Export --outdir '/tmp' '" . $path . "'");
                        $path = $path . ".pdf";
                    }
                    
                    Storage::disk('local')->putFileAs($last_id, $path, $filepath);
                }
            }

            $record = Record::create([
                'user_id' => Auth::id(),
                'applicant_name' => $applicant->name,
                'type' => 0,
                'after_status' => $applicant->status
            ]);
        });
        
        return redirect(route('applicant.list'))->with('success', '応募者情報を登録しました。');
    }

    public function get_applicant_info($id)
    {
        $permissions = checkPermission::check_permission();
        $applicant = Applicant::with(['department', 'user', 'reclite'])->where('id', $id)->first();
        if($applicant === null) {
            return redirect(route('applicant.list'))->withErrors(['notExistError' => '応募者情報はすでに削除されたか存在しません。']);
        }
        $departments = Department::all();
        $handlers = User::all();
        $reclites = Reclite::all();

        if($permissions) {
            $flag=false;
            foreach ($permissions as $permission) {
                if($permission === $applicant->department_id) {
                    $flag=true;
                }
            }
            if(!$flag){
                return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
            } 
        }

        $files = $applicant->files;
        return view('applicant.info', [
            'applicant' => $applicant,
            'files' => $files,
            'departments' => $departments,
            'handlers' => $handlers,
            'reclites' => $reclites
        ]);
    }

    public function download_file($id, $file_id, $filename)
    {
        $file = File::where('applicant_id', $id)->where('id', $file_id)->first();
        $filename = $file->filename;
        $path = storage_path('app/public/'. $id . '/' . $file->filepath);
        $headers = ['Content-disposition' => 'inline; filename="' . $filename . '.pdf"'];
        return response()->file($path, $headers);
    }

    public function edit_applicant($id)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $applicant = Applicant::with(['department', 'user', 'reclite'])->where('id', $id)->first();
        $departments = Department::all();
        $handlers = User::all();
        $reclites = Reclite::all();
        $status_list = ApplicantStatus::asSelectArray();

        $files = $applicant->files;
        return view('applicant.edit', [
            'applicant' => $applicant,
            'files' => $files,
            'departments' => $departments,
            'handlers' => $handlers,
            'reclites' => $reclites,
            'status_list' => $status_list,
        ]);
    }

    public function update_applicant($id, Request $request)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }
        
        $request = Validate::applicant_validation($request);

        $applicant = Applicant::where([
            'id' => $id,
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'mobile_phone' => $request->mobile_phone,
            'home_phone' => $request->home_phone,
            'department_id' => $request->department,
            'reclite_id' => $request->reclite,
            'user_id' => $request->handler,
            'status' => $request->status,
            'memo' => $request->memo
        ])->first();

        $flag = DB::transaction(function() use($request, $id, $applicant) {
            $flag=false;

            // 更新情報が存在する場合
            if(!$applicant) {
                $update_applicant = Applicant::where('id', $id)->first();
                $before_status = $update_applicant->status;
                $update_applicant->update([
                        'name' => $request->name,
                        'address' => $request->address,
                        'email' => $request->email,
                        'mobile_phone' => $request->mobile_phone,
                        'home_phone' => $request->home_phone,
                        'department_id' => $request->department,
                        'reclite_id' => $request->reclite,
                        'user_id' => $request->handler,
                        'status' => $request->status,
                        'memo' => $request->memo
                ]);
                // ステータスを更新している場合
                if ((int)$request->status !== $before_status) {
                    $record = Record::create([
                        'user_id' => Auth::id(),
                        'applicant_name' => $request->name,
                        'type' => 1,
                        'before_status' => $before_status,
                        'after_status' => $request->status 
                    ]);
                }
                $flag=true;
            }

            // 更新情報もなく、新規ファイルも存在しない場合にリダイレクト
            if ($applicant && $request->file === null) {
                return $flag=false;
            }

            // 新規ファイルが存在する場合
            if ($request->file !== null) {
                $files = $request->file('file');
                foreach($files as $file) {
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $filemime = $file->getClientOriginalExtension();
                    $filepath = Str::random(20);
                    $file_upload = File::create([
                        'applicant_id' => $id,
                        'filename' => pathinfo($filename, PATHINFO_FILENAME),
                        'filepath' => $filepath
                    ]);

                    $path = $file->getPathname();
                    if ($filemime !== "pdf") {
                        exec("export HOME=/tmp;/usr/bin/soffice --headless --convert-to pdf:writer_pdf_Export --outdir '/tmp' '" . $path . "'");
                        $path = $path . ".pdf";
                    }
                    
                    Storage::disk('local')->putFileAs($id, $path, $filepath);
                }
            }

            $record = Record::create([
                'user_id' => Auth::id(),
                'applicant_name' => $request->name,
                'type' => 2
            ]);

            return $flag=true;
        });

        if(!$flag) {
            return redirect(route('applicant.info', ['id' => $id]))->withErrors(['error' => '更新する情報がありませんでした。']);
        }
        return redirect(route('applicant.info', ['id' => $id]))->with('success', '応募者情報を更新しました。');
    }

    public function delete_applicant($id)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $delete = Applicant::where('id', $id)->first();
        $name = $delete->name;
        if (!$delete) {
            return redirect(route('applicant.list'))->withErrors(['exitError' => $id . 'は存在しないか既に削除されています。']);
        } else {
            $delete_files = $delete->files;
            
            DB::transaction(function () use($delete, $id, $delete_files, $name) {
                $delete->delete();
                
                Storage::deleteDirectory($id);
                foreach($delete_files as $file) {
                    $file->delete();
                }

                $record = Record::create([
                    'user_id' => Auth::id(),
                    'applicant_name' => $name,
                    'type' => 3
                ]);
            });
        }
        return redirect(route('applicant.list'))->with('success', '応募者情報を削除しました。');
    }

    public function delete_file($id, $file_id)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $file = File::with('applicant')->where('id', $file_id)->first();
        if(!$file) {
            return redirect(route('applicant.info', [ 'id' => $id ]))->withErrors(['exitError' => 'このファイルは削除できません。']);
        } else {
            $filename = $file->filename;
            $name = $file->applicant->name;
            $path = $id . '/' . $file->filepath;
            Storage::delete($path);
            DB::transaction(function() use($file, $id, $name, $filename){
                $file->delete();
                $record = Record::create([
                    'user_id' => Auth::id(),
                    'applicant_name' => $name,
                    'filename' => $filename,
                    'type' => 4
                ]);
            });
        }
        return redirect(route('applicant.info', ['id' => $id]))->with('success', 'ファイルを削除しました。');
    }
}
