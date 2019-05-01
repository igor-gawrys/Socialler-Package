<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Lesson.
 *
 * @package namespace Test\Entities;
 */
class Lesson extends Model implements Transformable
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
    protected $fillable = ['day_id','order','schedule_id','name','room_id','teacher_id','status'];
    public function room(){
      return $this->hasOne('App\Entities\Room','id','room_id');
    }
    public function teacher(){
      return $this->hasOne('App\Entities\Teacher','id','teacher_id');
    }
  

}
