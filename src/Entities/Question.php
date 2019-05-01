<?php

namespace Igorgawrys\Socialler\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Question.
 *
 * @package namespace Igorgawrys\Socialler\Entities;
 */
class Question extends Model implements Transformable
{
    use TransformableTrait;
       protected $casts = [
        'answers' => 'json'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image','content','answers','quiz_id'];
}
