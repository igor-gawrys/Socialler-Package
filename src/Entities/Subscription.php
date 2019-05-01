<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
/**
 * Class Subscription.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class Subscription extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','stripe_id','stripe_plan','quantity','trial_ends_at','ends_at'];
  /**
     * Get all of the subscriptions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function school()
    {
        return $this->hasOne('App\Entities\School','id','school_id');
    }
}
