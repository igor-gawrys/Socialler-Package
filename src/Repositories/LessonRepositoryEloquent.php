<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\LessonRepository;
use App\Entities\Lesson;
use App\Validators\LessonValidator;

/**
 * Class LessonRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class LessonRepositoryEloquent extends BaseRepository implements LessonRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Lesson::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
