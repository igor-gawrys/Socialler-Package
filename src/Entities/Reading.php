<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Sagalbot\Encryptable\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Reading.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class Reading extends Model implements Transformable
{
    use TransformableTrait;
   // use Encryptable;
    /**
       * The attributes that should be encrypted when stored.
       *
       * @var array
       */
    //protected $encryptable = ['title','description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','description','year','cover','background','grade_id','status'];
    public function grade(){
      return $this->hasOne("App\Entities\Grade","id","grade_id");
    }

}