<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ReadingRepository;
use App\Entities\Reading;
use App\Validators\ReadingValidator;

/**
 * Class ReadingRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class ReadingRepositoryEloquent extends BaseRepository implements ReadingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Reading::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ReadingValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
