<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Post.
 *
 * @package namespace Test\Entities;
 */
class Post extends Model implements Transformable
{
  use TransformableTrait;
  use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    
       protected $casts = [
        'images' => 'json',
          'videos' => 'json',
          'files' => 'json'
    ];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['content','images','user_id','grade_id','files','videos','order','user_id','school_id'];
  public function user(){
    return $this->HasOne('App\Entities\User','id','user_id');
  }
  public function comments(){
    return $this->hasMany('App\Entities\Comment','post_id','id');
  }
  public function grade(){
      return $this->HasOne('App\Entities\Grade','id','grade_id');
  }
  public function trusts(){
     return $this->HasMany('App\Entities\Trust','post_id','id');
  }
}
