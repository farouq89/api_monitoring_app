<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitorApi extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $table = 'monitor_apis';
    public $incrementing = false;
    
    protected $fillable = [
        'name', 'url', 'json', 'methode', 'expected_response_code', 
        'email', 'previous_api_status', 'interval', 'avg_response_time'
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function publicPages() {
        return $this->hasMany(ApiPublicPage::class, 'rel_id');
    } 

    public function incidents() {
        return $this->hasMany(ApiIncident::class, 'rel_id');
    } 

    public function logs() {
        return $this->hasMany(MonitorApiLog::class, 'rel_id');
    } 

    protected static function boot() {
        parent::boot();
    
        static::deleting(function($api) {
            $api->publicPages()->delete();
            $api->incidents()->delete();
            $api->logs()->delete();
        });
    }
}
