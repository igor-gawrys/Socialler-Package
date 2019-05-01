<?php

namespace Igorgawrys\Socialler\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
$this->app->bind(\App\Repositories\SchoolRepository::class, \App\Repositories\SchoolRepositoryEloquent::class);
$this->app->bind(\App\Repositories\MessageRepository::class, \App\Repositories\MessageRepositoryEloquent::class);
$this->app->bind(\App\Repositories\GradeRepository::class, \App\Repositories\GradeRepositoryEloquent::class);
$this->app->bind(\App\Repositories\RoleRepository::class, \App\Repositories\RoleRepositoryEloquent::class);
$this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
$this->app->bind(\App\Repositories\ScheduleRepository::class, \App\Repositories\ScheduleRepositoryEloquent::class);
$this->app->bind(\App\Repositories\EventRepository::class, \App\Repositories\EventRepositoryEloquent::class);
$this->app->bind(\App\Repositories\SubscriptionRepository::class, \App\Repositories\SubscriptionRepositoryEloquent::class);
$this->app->bind(\App\Repositories\LessonRepository::class, \App\Repositories\LessonRepositoryEloquent::class);
$this->app->bind(\App\Repositories\RoomRepository::class, \App\Repositories\RoomRepositoryEloquent::class);
$this->app->bind(\App\Repositories\TeacherRepository::class, \App\Repositories\TeacherRepositoryEloquent::class);
$this->app->bind(\App\Repositories\DayRepository::class, \App\Repositories\DayRepositoryEloquent::class);
$this->app->bind(\App\Repositories\CommentRepository::class, \App\Repositories\CommentRepositoryEloquent::class);
$this->app->bind(\App\Repositories\AnswerRepository::class, \App\Repositories\AnswerRepositoryEloquent::class);
$this->app->bind(\App\Repositories\PostRepository::class, \App\Repositories\PostRepositoryEloquent::class);
$this->app->bind(\App\Repositories\TrustRepository::class, \App\Repositories\TrustRepositoryEloquent::class);
$this->app->bind(\App\Repositories\MessagesAllRepository::class, \App\Repositories\MessagesAllRepositoryEloquent::class);
$this->app->bind(\App\Repositories\BadgeRepository::class, \App\Repositories\BadgeRepositoryEloquent::class);
$this->app->bind(\App\Repositories\GiveBadgeRepository::class, \App\Repositories\GiveBadgeRepositoryEloquent::class);
$this->app->bind(\App\Repositories\PointRepository::class, \App\Repositories\PointRepositoryEloquent::class);
$this->app->bind(\App\Repositories\LockRepository::class, \App\Repositories\LockRepositoryEloquent::class);
$this->app->bind(\App\Repositories\ReadingRepository::class, \App\Repositories\ReadingRepositoryEloquent::class);
$this->app->bind(\App\Repositories\NoteRepository::class, \App\Repositories\NoteRepositoryEloquent::class);
      $this->app->bind(\App\Repositories\QuizRepository::class, \App\Repositories\QuizRepositoryEloquent::class);
    }
}
