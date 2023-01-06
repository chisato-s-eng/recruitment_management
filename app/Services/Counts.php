<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Applicant;
use App\Models\Record;
use App\Enums\ApplicantStatus;

class Counts
{
  public static function applicant_counts() 
  {
    $applicant_data_counts = Applicant::orderBy('status')->get()->groupBy('status')
        ->map(function($status) {
            return $status->count();
        })
        ->toArray();

    $status_list = ApplicantStatus::asSelectArray();
    $applicant_counts = array();
    foreach($status_list as $status_key => $status_value) {
        if(array_key_exists($status_key, $applicant_data_counts)) {
            $applicant_counts[] = $applicant_data_counts[$status_key];
        } else {
            $applicant_counts[] = 0;
        }
    }

    return [$applicant_counts, $status_list];
  }

  public static function applicant_counts_for_department($department_id)
  {
    $applicant_data_counts = Applicant::where('department_id', $department_id)->orderBy('status')->get()->groupBy('status')
            ->map(function($status) {
                return $status->count();
            })
            ->toArray();

    $status_list = ApplicantStatus::asSelectArray();
    $applicant_counts = array();
    foreach($status_list as $status_key => $status_value) {
        if(array_key_exists($status_key, $applicant_data_counts)) {
            $applicant_counts[] = $applicant_data_counts[$status_key];
        } else {
            $applicant_counts[] = 0;
        }
    }

    return $applicant_counts;
  }

  public static function status_counts()
  {
    $application = Record::whereIn('type', [0, 1])->distinct('applicant_name')->count();
    $document_screening = Record::where('after_status', 1)->distinct('applicant_name')->count();
    $first = Record::whereIn('after_status', [2,3])->distinct('applicant_name')->count();
    $second = Record::whereIn('after_status', [4,5])->distinct('applicant_name')->count();
    $unofficial_offer = Record::where('after_status', 6)->distinct('applicant_name')->count();
    $accept_offer = Record::where('after_status', 7)->distinct('applicant_name')->count();
    $failure = Record::where('after_status', 8)->distinct('applicant_name')->count();
    $status_counts = [$application, $document_screening, $first, $second, $unofficial_offer, $accept_offer, $failure];
    
    return $status_counts;
  }

  public static function probability($request)
  {
    if ((int)$request->before_status === 0) {
        $before = Record::whereIn('type', [0, 1])->distinct('applicant_name')->count();
    } elseif ((int)$request->before_status === 1) {
        $before = Record::where('after_status', 1)->count();
    } elseif ((int)$request->before_status === 2) {
        $before = Record::whereIn('after_status', [2,3])->distinct('applicant_name')->count();
    } elseif ((int)$request->before_status === 3) {
        $before = Record::whereIn('after_status', [4,5])->distinct('applicant_name')->count();
    } elseif ((int)$request->before_status === 4) {
        $before = Record::where('after_status', 6)->count();
    } elseif ((int)$request->before_status === 5) {
        $before = Record::where('after_status', 7)->count();
    } elseif ((int)$request->before_status === 6) {
        $before = Record::where('after_status', 8)->count();
    }

    if ((int)$request->after_status === 0) {
        $after = Record::whereIn('type', [0, 1])->distinct('applicant_name')->count();
    } elseif ((int)$request->after_status === 1) {
        $after = Record::where('after_status', 1)->count();
    } elseif ((int)$request->after_status === 2) {
        $after = Record::whereIn('after_status', [2,3])->distinct('applicant_name')->count();
    } elseif ((int)$request->after_status === 3) {
        $after = Record::whereIn('after_status', [4,5])->distinct('applicant_name')->count();
    } elseif ((int)$request->after_status === 4) {
        $after = Record::where('after_status', 6)->count();
    } elseif ((int)$request->after_status === 5) {
        $after = Record::where('after_status', 7)->count();
    } elseif ((int)$request->after_status === 6) {
        $after = Record::where('after_status', 8)->count();
    }

    $data = round($after / $before * 100, 1);
    return [$data, $before, $after];
  }
}
