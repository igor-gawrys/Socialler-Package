<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GiveBadgeRepository;
use App\Entities\GiveBadge;
use App\Validators\GiveBadgeValidator;

/**
 * Class GiveBadgeRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class GiveBadgeRepositoryEloquent extends BaseRepository implements GiveBadgeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GiveBadge::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
