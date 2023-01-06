<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * 応募者一覧へのリレーション
     */
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
