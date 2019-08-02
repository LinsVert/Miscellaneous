<?php
namespace Linsvert\Spider\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    //laravel-admin-spider task model
    protected $table = 'spider_task';
    
    public function spider()
    {
        return $this->hasOne(SpiderModel::class, 'id', 'spider_id');
    }
    
}
