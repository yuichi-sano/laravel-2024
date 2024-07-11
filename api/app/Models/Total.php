<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    use HasFactory;

    protected $primaryKey = 'total_id';

    protected $fillable = [
        'total_name',
        'total_value'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scaffold()
    {
        return $this->belongsTo(Scaffold::class);
    }
}