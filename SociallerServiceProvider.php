<?php

namespace Igorgawrys\Socialler\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use App\Entities\School;

class SociallerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

            $this->loadMigrationsFrom(__DIR__.'/migrations');

            $this->loadViewsFrom(__DIR__.'/src/resources/views', 'socialler');

            $this->loadRoutesFrom(__DIR__.'/src/routes/api.php');

            $this->loadRoutesFrom(__DIR__.'/src/routes/web.php');

            $this->loadRoutesFrom(__DIR__.'/src/routes/channels.php');

            $this->loadRoutesFrom(__DIR__.'/src/routes/console.php');

            $this->publishes([

                __DIR__.'/config/laravolt/avatar.php' => config_path('laravolt/avatar.php'),
   
                __DIR__.'/config/broadcasting.php' => config_path('broadcasting.php'),
   
                __DIR__.'/config/jwt.php' => config_path('jwt.php'),
   
                __DIR__.'/config/repository.php' => config_path('repository.php'),
   
                __DIR__.'/resources/views' => resource_path('views/vendor/socialler'),
              
            ]);

            $this->publishes([
                
                __DIR__.'/assets' => public_path('vendor/courier'),

            ], 'public');

            $this->app['router']->aliasMiddleware('grade_perrmission', \Igorgawrys\Socialler\Http\Middleware\GradePerrmission::class);

            $this->app['router']->aliasMiddleware('admin_perrmission', \Igorgawrys\Socialler\Http\Middleware\AdminPerrmission::class);

            $this->app['router']->aliasMiddleware('post_perrmission', \Igorgawrys\Socialler\Http\Middleware\PostPerrmission::class);

            $this->app['router']->aliasMiddleware('comment_perrmission', \Igorgawrys\Socialler\Http\Middleware\CommentPerrmission::class);

            $this->app['router']->aliasMiddleware('answer_perrmission', \Igorgawrys\Socialler\Http\Middleware\AnswerPerrmission::class);

            $this->app['router']->pushMiddlewareToGroup('api', \Barryvdh\Cors\HandleCors::class);

           Validator::extend('school_key_correct', function ($attribute, $value, $parameters, $validator) {

                $request = $this->app->make('Illuminate\Http\Request');
                
                $school = School::find($request->input('school_id'));

               return $school->verify_key==$value;

          });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
