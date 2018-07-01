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
        'skill_group_id', 'name', 
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($model)
        {
            if ($model->isForceDeleting()) {
                $model->users()->detach();
            }
        });

        //static::addGlobalScope(new MandantScope);
    }


    public function skillGroup() 
    {
        return $this->belongsTo(SkillGroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->using(UserSkill::class)
            ->as('user_skill')
            ->withPivot('grade')
            ->withTimestamps()
        ;
    }
}
