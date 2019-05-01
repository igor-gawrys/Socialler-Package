<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Trust.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class Trust extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','post_id'];
    public function Post(){
    	return $this->hasOne('App\Entities\Post','id','post_id');
    }
    public function User(){
    	return $this->hasOne('App\Entities\User','id','user_id');
    }
}
