<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    //use SoftDeletes

    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'users';

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
        'login', 'name', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
            //if ($model->forceDeleting) {
                $model->skills()->detach();
            //}
        });

        //static::addGlobalScope(new MandantScope);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->using(UserSkill::class)
            ->as('user_skill')
            ->withPivot('grade')
            ->withTimestamps()
        ;
    }

    public function getUserSkill(int $skillId)
    {
        $result = null;
        foreach($this->skills as $skill) {
            if ($skill->id === $skillId) {
                $result = $skill->user_skill;
                break;
            }
        }
        return $result;
    }
}
