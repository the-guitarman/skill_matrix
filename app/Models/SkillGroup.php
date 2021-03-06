<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkillGroup extends Model
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function skills() 
    {
        return $this->hasMany(Skill::class);
    }

    public function users() 
    {
        $skillIds = $this->skills->pluck('id')->all();
        $userIds = UserSkill::whereIn('skill_id', $skillIds)->pluck('user_id')->all();
        $userIds = array_unique($userIds);
        return User::whereIn('id', $userIds)->orderBy('name', 'asc');
    }
}
