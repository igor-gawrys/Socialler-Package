<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Sagalbot\Encryptable\Encryptable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use App\Entities\Subscription;
/**
 * Class School.
 *
 * @package namespace Test\Entities;
 */
class School extends Model implements Transformable
{
    use TransformableTrait;
    use Encryptable;
    use SoftDeletes;
    use Billable;
    
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
    protected $encryptable = ['name','description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','description','website','user_id','avatar','short_name','verify_key','verifed_at','address','place','voivodeship','stripe_id',"card_brand","card_last_four"];
 public function user(){
       return $this->hasOne('App\Entities\User','id','user_id');
   }
   public function users(){
       return $this->hasMany('App\Entities\User','school_id','id');
   }
   public function grades(){
       return $this->hasMany('App\Entities\Grade','school_id','id');
   }
   public function posts(){
       return $this->hasMany('App\Entities\Post','school_id','id')->where('grade_id',null);
   }
   /**
     * Get all of the subscriptions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->orderBy('created_at', 'desc');
    }
}
