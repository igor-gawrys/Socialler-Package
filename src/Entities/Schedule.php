<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Schedule.
 *
 * @package namespace Test\Entities;
 */
class Schedule extends Model implements Transformable
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
    protected $fillable = ['grade_id'];
    
    public function days(){
      return $this->hasMany('App\Entities\Day','schedule_id','id');
    }
   public function rooms(){
      return $this->hasMany('App\Entities\Room','schedule_id','id');
    }
     public function teachers(){
      return $this->hasMany('App\Entities\Teacher','schedule_id','id');
    }
  

}
