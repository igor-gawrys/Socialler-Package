<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TrustRepository;
use App\Entities\Trust;
use App\Validators\TrustValidator;

/**
 * Class TrustRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class TrustRepositoryEloquent extends BaseRepository implements TrustRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Trust::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
