<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MessagesAllRepository;
use App\Entities\MessagesAll;
use App\Validators\MessagesAllValidator;

/**
 * Class MessagesAllRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class MessagesAllRepositoryEloquent extends BaseRepository implements MessagesAllRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MessagesAll::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return MessagesAllValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
