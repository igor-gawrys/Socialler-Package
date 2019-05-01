<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BadgeRepository;
use App\Entities\Badge;
use App\Validators\BadgeValidator;

/**
 * Class BadgeRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class BadgeRepositoryEloquent extends BaseRepository implements BadgeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Badge::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return BadgeValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
