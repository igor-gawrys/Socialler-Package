<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GradeRepository;
use App\Entities\Grade;
use App\Validators\GradeValidator;
use Illuminate\Support\Facades\Auth;

/**
 * Class MessageRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class GradeRepositoryEloquent extends BaseRepository implements GradeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Grade::class;
    }

    /**
    * Specify Validator class name
    *
    * 
    *    * @return mixed
    */
    public function validator()
    {
        return GradeValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
