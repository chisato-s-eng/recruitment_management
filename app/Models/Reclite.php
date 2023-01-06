<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Reclite extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'name', 
        'department_id',
        'user_id',
        'status',
        'memo'
    ];

    protected $guarded = [];

    public $sortable = ['name', 'user_id', 'department_id', 'status', 'updated_at'];

    /**
     * 応募者一覧へのリレーション
     */
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    /**
     * 部署テーブルへのリレーション
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * ユーザーテーブルへのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

