<?php

namespace App\Providers;

use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\Eloquent\EloquentTransactionManager;
use App\Services\Helper\TransactionManagerInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
        }

        foreach (glob(app_path('Services/*ServiceInterface.php')) as $service) {
            $serviceName = explode('Interface.php', basename($service))[0];
            $this->app->singleton(
                sprintf('App\\Services\\%sInterface', $serviceName),
                sprintf('App\\Services\\%s', $serviceName)
            );
        }

        foreach (glob(app_path('Repositories/*Interface.php')) as $repository) {
            $serviceName = explode('Interface.php', basename($repository))[0];
            if($serviceName === 'BaseRepository'){
                continue;
            }
            $this->app->singleton(
                sprintf('App\\Repositories\\%sInterface', $serviceName),
                sprintf('App\\Repositories\\Eloquent\\%s', $serviceName)
            );
        }
        $this->app->singleton(
            BaseRepositoryInterface::class,
            EloquentRepository::class
        );

        $this->app->singleton(
            TransactionManagerInterface::class,
            EloquentTransactionManager::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extendImplicit('cognito_user_unique', 'App\Validators\CognitoUserUniqueValidator@validate');
    }
}
