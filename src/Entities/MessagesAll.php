<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Sagalbot\Encryptable\Encryptable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class MessagesAll.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class MessagesAll extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    use Encryptable;
    /**
       * The attributes that should be encrypted when stored.
       *
       * @var array
       */
    protected $encryptable = ['content'];
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
    protected $fillable = ['content','user_id','grade_id'];
    public function user(){
    	return $this->hasOne('App\Entities\User','id','user_id');
    }
    public function grade(){
    		return $this->hasOne('App\Entities\Grade','id','grade_id');
    }

}
