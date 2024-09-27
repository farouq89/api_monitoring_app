<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitorLog extends Model
{    
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    public function servers() {
        return $this->belongsTo(MonitorServer::class, 'rel_id');
    } 
}
