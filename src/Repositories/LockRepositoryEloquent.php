<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\LockRepository;
use App\Entities\Lock;
use App\Validators\LockValidator;

/**
 * Class LockRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class LockRepositoryEloquent extends BaseRepository implements LockRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Lock::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return LockValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
