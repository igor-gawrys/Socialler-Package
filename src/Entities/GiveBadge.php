<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class GiveBadge.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class GiveBadge extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','badge_id'];
    public function user(){
      return $this->hasOne('App\Entities\User','id','user_id');
    }
    public function badge(){
    	 return $this->hasOne('App\Entities\Badge','id','badge_id');
    }
}
