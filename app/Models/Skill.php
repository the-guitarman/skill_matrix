<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
    ];


    public function skillGroup() 
    {
        return $this->belongsTo(SkillGroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('grade')
            ->withTimestamps()
        ;
    }
}
