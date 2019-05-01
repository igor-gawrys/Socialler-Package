<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ScheduleRepository;
use App\Entities\Schedule;
use App\Validators\ScheduleValidator;

/**
 * Class ScheduleRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class ScheduleRepositoryEloquent extends BaseRepository implements ScheduleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Schedule::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ScheduleValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
