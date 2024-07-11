<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $primaryKey = [
        'Result_id'
    ];

    protected $fillable = [
        'quantity'
    ];

    public function part()
    {
        return $this->hasOne(Part::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}