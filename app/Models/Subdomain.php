<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'toml',
        'expiration_date'
    ];

    

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(){
        return $this->expiration_date < now();
    }

    public static function getExpired(){
        return self::where('expiration_date', '<', now())->get();
    }

    public function getData(){
        $data = json_decode($this->toml);
        $data->name = $this->name;
        return $data;
    }
}
