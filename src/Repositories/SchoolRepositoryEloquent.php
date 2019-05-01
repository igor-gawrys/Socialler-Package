<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SchoolRepository;
use App\Entities\School;
use App\Validators\SchoolValidator;

/**
 * Class SchoolRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class SchoolRepositoryEloquent extends BaseRepository implements SchoolRepository
{
		/**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return School::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
