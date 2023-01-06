<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Carbon\Carbon;

class RecordController extends Controller
{
    //
    public function get_records() {

        $records = Record::with(['user', 'applicant'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y年m月d日');
            });

        //dd($records);
        return view('top', [
            'records' => $records
        ]);
    }
}
