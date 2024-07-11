<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $primaryKey = [
        'part_code'
    ];

    protected $fillable = [
        'part_name'
    ];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}