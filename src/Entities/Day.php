<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Day.
 *
 * @package namespace Test\Entities;
 */
class Day extends Model implements Transformable
{
    use TransformableTrait;
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','description','avatar','day_number','order','schedule_id'];
    
    
    public function events(){
      return $this->hasMany('App\Entities\Event','day_id','id');
    }
    public function lessons(){
      return $this->hasMany('App\Entities\Lesson','day_id','id');
    }

}
