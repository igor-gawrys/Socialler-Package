<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\QuizRepository;
use App\Entities\Quiz;
use App\Validators\QuizValidator;

/**
 * Class QuizRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class QuizRepositoryEloquent extends BaseRepository implements QuizRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Quiz::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return QuizValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
