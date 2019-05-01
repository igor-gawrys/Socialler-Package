<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PointRepository;
use App\Entities\Point;
use App\Validators\PointValidator;

/**
 * Class PointRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class PointRepositoryEloquent extends BaseRepository implements PointRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Point::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PointValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
