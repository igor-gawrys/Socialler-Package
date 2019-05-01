<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Sagalbot\Encryptable\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Message.
 *
 * @package namespace Test\Entities;
 */
class Message extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
      use Encryptable;

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];
  /**
   * The attributes that should be encrypted when stored.
   *
   * @var array
   */
protected $encryptable = ['content'];
  
       protected $casts = [
        'images' => 'json',
         'files' => 'json'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content','written_id','user_id','images','files'];
    public function user(){
    return $this->hasOne('App\Entities\User','id','user_id');
    }
    public function written(){
return     $this->hasOne('App\Entities\user','id','written_id');
    }

}
