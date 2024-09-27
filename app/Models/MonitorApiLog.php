<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitorApiLog extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    use SoftDeletes;
    use HasFactory;

    public function apis() {
        return $this->belongsTo(MonitorApi::class, 'rel_id');
    } 
}
