<?php

namespace App\Providers;

use App\Repositories\AttendanceRepositories\AttendanceRepositoryInterface;
use App\Repositories\AttendanceRepositories\EloquentAttendanceRepository;

use App\Repositories\EvaluationRepositories\EloquentEvaluationRepository;
use App\Repositories\EvaluationRepositories\EvaluationRepositoryInterface;
    
use App\Repositories\JustificationRepositories\EloquentJustificationRepository;
use App\Repositories\ProfileRepositories\EloquentPositionRepository;

use App\Repositories\UserRepositories\EloquentUserRepository;
use App\Repositories\UserRepositories\UserRepositoryInterface;

use App\Repositories\JustificationRepositories\JustificationRepositoryInterface;

use App\Repositories\ProfileRepositories\PositionRepositoryInterface;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, EloquentPositionRepository::class);
        $this->app->bind(JustificationRepositoryInterface::class, EloquentJustificationRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, EloquentAttendanceRepository::class);
        $this->app->bind(EvaluationRepositoryInterface::class, EloquentEvaluationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
