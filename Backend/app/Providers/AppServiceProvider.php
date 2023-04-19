<?php

namespace App\Providers;

use App\Repositories\Ticket\TicketRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTicketRepository();
        $this->registerUserRepository();
    }

    public function registerTicketRepository()
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
    }

    public function registerUserRepository()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
