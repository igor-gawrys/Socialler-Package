<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Sagalbot\Encryptable\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Teacher.
 *
 * @package namespace Test\Entities;
 */
class Teacher extends Model implements Transformable
{
    use TransformableTrait;
   use Encryptable;
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
     /**
      * The attributes that should be encrypted when stored.
      *
      * @var array
      */
     protected $encryptable = ['full_name'];
    protected $fillable = ['full_name','default_room_id','schedule_id'];
  public function room(){
    return $this->hasOne('App\Entities\Room','id','default_room_id');
    }
 public function lesson(){
    return $this->hasOne('App\Entities\Lesson','teacher_id','id');
    }
}
