<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Note.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class Note extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','content','user_id'];
    public function user(){
    	return $this->hasOne('App\Entities\Note','user_id','grade_id');
    }
}
