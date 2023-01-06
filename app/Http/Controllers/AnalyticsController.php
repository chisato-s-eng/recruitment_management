<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Record;
use App\Models\Applicant;
use App\Models\Department;
use App\Enums\ApplicantStatus;
use App\Enums\Status;
use App\Services\Counts;

class AnalyticsController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $departments = Department::all();
        $result = Counts::applicant_counts();
        $status_result = Counts::status_counts();
        $status = Status::asSelectArray();

        return view('analytics.index', [
            'applicant_counts' => $result[0],
            'status_list' => $result[1],
            'departments' => $departments,
            'status_counts' => $status_result,
            'status' => $status,
        ]);
    }

    public function show_data_for_department(Request $request)
    {
        $request->validate([
            'department' => 'nullable|exists:App\Models\Department,id',
        ]);

        $departments = Department::all();
        if($request->department === null) {
            $result = Counts::applicant_counts();
            $applicant_counts = $result[0];
        } else {
            $applicant_counts = Counts::applicant_counts_for_department($request->department);
        }
        
        return $applicant_counts;
    }

    public function show_probability_for_change_status(Request $request) {
        $request->validate([
            'before_status' => 'required|integer|between:0,6',
            'after_status' => 'required|integer|between:0,6'
        ]);

        $data = Counts::probability($request);
        return $data;
    }
}
