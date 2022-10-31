<?php

namespace App\Providers;

use App\Observers\Document\DocumentObserver;
use App\Models\Document\Document;
use App\Repositories\Contracts\Document\DocumentUserRepositoryInterface;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Repositories\Document\DocumentUserRepository;
use App\Repositories\User\UserRepository;
use App\Services\Contracts\DocumentUserServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Document\DocumentService;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Document\DocumentRepository;
use App\Services\Contracts\DocumentServiceInterface;
use App\Services\Document\DocumentUserService;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public array $singletons = [
        //Repositories
        DocumentRepositoryInterface::class => DocumentRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        DocumentUserRepositoryInterface::class => DocumentUserRepository::class,



        //services
        DocumentServiceInterface::class => DocumentService::class,
        UserServiceInterface::class => UserService::class,
        DocumentUserServiceInterface::class => DocumentUserService::class,

    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Document::observe(DocumentObserver::class);
    }
}
