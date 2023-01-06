<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name', 
        'user_id', 
        'type', 
        'filename',
        'before_status', 
        'after_status' 
    ];

    protected $guarded = [];

    /**
     * ユーザーテーブルへのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
      * 応募者テーブルへのリレーション
      */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
