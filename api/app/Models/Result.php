<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $primaryKey = 'result_id';

    protected $fillable = [
        'quantity'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}