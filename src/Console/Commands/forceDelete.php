<?php

namespace Igorgawrys\Socialler\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Answer;
use App\Entities\Comment;
use App\Entities\Event;
  use App\Entities\Grade;
    use App\Entities\Lesson;
     use App\Entities\Message;
     use App\Entities\MessagesAll;
       use App\Entities\Post;
   use App\Entities\Reading;
  use App\Entities\Room;
    use App\Entities\Schedule;
    use App\Entities\School;
    use App\Entities\Teacher;
     use App\Entities\User;
class forceDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'force:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All soft deletes force delete';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Answer::whereNotNull('deleted_at')->forceDelete();
       Comment::whereNotNull('deleted_at')->forceDelete();
        Event::whereNotNull('deleted_at')->forceDelete();
         Grade::whereNotNull('deleted_at')->forceDelete();
        Lesson::whereNotNull('deleted_at')->forceDelete();
          Message::whereNotNull('deleted_at')->forceDelete();
       MessagesAll::whereNotNull('deleted_at')->forceDelete();
        Post::whereNotNull('deleted_at')->forceDelete();
      Reading::whereNotNull('deleted_at')->forceDelete();
       Room::whereNotNull('deleted_at')->forceDelete();
            Schedule::whereNotNull('deleted_at')->forceDelete();
          School::whereNotNull('deleted_at')->forceDelete();
        Teacher::whereNotNull('deleted_at')->forceDelete();
      User::whereNotNull('deleted_at')->forceDelete();
    }
}
