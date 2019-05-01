<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DayRepository;
use App\Entities\Day;
use App\Validators\DayValidator;

/**
 * Class DayRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class DayRepositoryEloquent extends BaseRepository implements DayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Day::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DayValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
