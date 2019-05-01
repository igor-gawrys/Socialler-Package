<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Grade.
 *
 * @package namespace Test\Entities;
 */
class Grade extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','description','avatar','user_id','type','school_id','join_key','verifed_at','color'];
    public function school(){
      return $this->hasOne('App\Entities\School','id','school_id');
    }
    public function user(){
      return $this->hasOne('App\Entities\User','id','user_id');
    }
      public function users(){
      return $this->hasMany('App\Entities\User','grade_id','id');
    }
    public function posts(){
      return $this->hasMany('App\Entities\Post','grade_id','id');
    }
     public function schedule(){
      return $this->hasMany('App\Entities\Schedule','grade_id','id');
    }
    public function readings(){
        return $this->hasMany('App\Entities\Reading','grade_id','id');
    }
    public function messagesAlls(){
       return $this->hasMany('App\Entities\MessagesAll','grade_id','id');
    }
  public function quizes(){
    return $this->hasMany('App\Entities\Quiz','grade_id','id');
    }
}
