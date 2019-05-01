<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment.
 *
 * @package namespace Test\Entities;
 */
class Comment extends Model implements Transformable
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
        'images' => 'json'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content','images','type','user_id','post_id','order','user_id'];
    public function user(){
      return $this->hasOne('App\Entities\User','id','user_id');
    }
   public function answers(){
     return $this->hasMany('App\Entities\Answer','comment_id','id');
   }

}
