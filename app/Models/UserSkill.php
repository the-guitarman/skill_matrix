<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSkill extends Pivot
{
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_skills';

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
        'grade', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function skill() 
    {
        return $this->belongsTo(Skill::class);
    }
}
