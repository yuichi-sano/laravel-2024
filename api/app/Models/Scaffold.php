<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scaffold extends Model
{
    use HasFactory;

    protected $primaryKey = [
        'scaffold_id'
    ];

    protected $fillable = [
        'scaffold_name'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function total()
    {
        return $this->belongsTo(Total::class);
    }
}