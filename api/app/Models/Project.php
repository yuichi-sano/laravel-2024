<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = [
        'project_id'
    ];

    protected $fillable = [
        'project_name'
    ];

    public function scaffold()
    {
        return $this->hasOne(Scaffold::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }

    public function position()
    {
        return $this->hasOne(Position::class);
    }

    public function total()
    {
        return $this->hasOne(Total::class);
    }
}