<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Lock.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class Lock extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content'];
    public function user(){
    	return $this->hasOne('App\Entities\User','lock_id','id');
    }

}
