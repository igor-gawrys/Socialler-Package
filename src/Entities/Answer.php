<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Answer.
 *
 * @package namespace Test\Entities;
 */
class Answer extends Model implements Transformable
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
  protected $fillable = ['content','image','type','comment_id','user_id'];
  public function user(){
    return $this->hasOne('App\Entities\User','id','user_id');
  }

}
