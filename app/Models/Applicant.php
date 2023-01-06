<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Applicant extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'name', 
        'address', 
        'email', 
        'mobile_phone', 
        'home_phone', 
        'status',
        'department_id',
        'reclite_id',
        'user_id',
        'memo'
    ];

    protected $guarded = ['id'];

    public $sortable = ['name', 'status', 'department_id', 'user_id', 'updated_at'];

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

    /**
     * 求人テーブルへのリレーション
     */
    public function reclite()
    {
        return $this->belongsTo(Reclite::class);
    }

    /**
     * ファイルテーブルへのリレーション
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
