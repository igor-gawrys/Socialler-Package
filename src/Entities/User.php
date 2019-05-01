<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Sagalbot\Encryptable\Encryptable;
use App\Events\PointUpdatedEvent;

/**
 * Class User.
 *
 * @package namespace Test\Entities;
 */
class User extends  Authenticatable implements Transformable,JWTSubject
{
    use TransformableTrait;
    use Notifiable;
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
protected $encryptable = ['full_name'];


      /**
       * The attributes that are mass assignable.
       *
       * @var array
       */
      protected $fillable = [
          'full_name', 'email', 'password','grade_id','avatar','role_id','first_logged','lock_id','school_id','qr_code','social_code'
      ];

      /**
       * The attributes that should be hidden for arrays.
       *
       * @var array
       */
      protected $hidden = [
          'password','remember_token'
      ];
      public function posts(){
        return $this->hasMay('App\Entities\Post','user_id','id');
      }
   /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
   public function getJWTIdentifier()
   {
       return $this->getKey();
   }

   /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
   public function getJWTCustomClaims()
   {
       return [];
   }
   public function role(){
     return $this->hasOne('App\Entities\Role','id','role_id');
   }
   public function grade(){
       return $this->hasOne('App\Entities\Grade','id','grade_id');
   }
   public function school(){
       return $this->hasOne('App\Entities\School','id','school_id');
   }
   public function point(){
        return $this->hasOne('App\Entities\Point','user_id','id');
   }
    public function giveBadges(){
      return $this->hasMany('App\Entities\GiveBadge','user_id','id');
    }
       public function addPoint($add = 1){

     $point = $this->point()->update(['quantity' => $this->point()->first()->quantity + $add]);
      event(new PointUpdatedEvent($point));
      
    }
    public function subtractPoint($subtract = 1){
        $point = $this->point()->update(['quantity' => $this->point()->first()->quantity - $subtract]);
        event(new PointUpdatedEvent($point));
    }
       public function lock(){
      return $this->hasOne('App\Entities\Lock','id','lock_id');
    }
    public function notes(){
      return $this->hasMany('App\Entities\Note','user_id','id');
    }
}
