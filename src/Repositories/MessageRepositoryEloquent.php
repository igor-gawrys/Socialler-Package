<?php

namespace Igorgawrys\Socialler\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MessageRepository;
use App\Entities\Message;
use App\Validators\MessageValidator;
use Illuminate\Support\Facades\Auth;


/**
 * Class MessageRepositoryEloquent.
 *
 * @package namespace Igorgawrys\Socialler\Repositories;
 */
class MessageRepositoryEloquent extends BaseRepository implements MessageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Message::class;
    }

    /**
    * Specify Validator class name
    *
    * 
    *    * @return mixed
    */
    public function validator()
    {
        return MessageValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    public function UserGetMessages($user_id){
    	$messages = Message::where('user_id','=',Auth::id())->where('written_id','=',$user_id)->orwhere('user_id','=',$user_id)->where('written_id','=',Auth::id())->with(['written' => function($query){$query->with('role');},'user' => function($query){$query->with('role');}])->get();
    	return $messages;
    }
}
