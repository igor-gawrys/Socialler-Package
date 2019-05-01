<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Room.
 *
 * @package namespace Test\Entities;
 */
class Room extends Model implements Transformable
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
    protected $fillable = ['name','schedule_id','number'];
     public function lesson(){
    return $this->hasOne('App\Entities\Lesson','room_id','id');
    }
}
