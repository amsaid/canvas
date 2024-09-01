<?php

namespace Canvas;

use Canvas\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Canvas\Commands\CanvasCommand;
use Canvas\Console\DigestCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Canvas\Console\MigrateCommand;
use Canvas\Console\PublishCommand;
use Canvas\Console\UiCommand;
use Canvas\Console\UserCommand;
use Canvas\Events\PostViewed;
use Canvas\Listeners\CaptureView;
use Canvas\Listeners\CaptureVisit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Route;

class CanvasServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('canvas')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets()
            ->publishesServiceProvider('providers/CanvasServiceProvider')
            ->hasMigration('create_canvas_table')
            //->hasCommand(CanvasCommand::class)
            ->hasCommand(DigestCommand::class)
            //->hasCommand(InstallCommand::class)
            ->hasCommand(MigrateCommand::class)
            ->hasCommand(PublishCommand::class)
            ->hasCommand(UiCommand::class)
            ->hasCommand(UserCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishAssets()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $command) {
                        $email = 'email@example.com';
                        $password = 'password';
                        User::create([
                            'id' => Uuid::uuid4()->toString(),
                            'name' => 'Example User',
                            'email' => $email,
                            'password' => Hash::make($password),
                            'role' => User::ADMIN,
                        ]);

                        $command->table(['Default Email', 'Default Password'], [[$email, $password]]);
                        $command->info('First things first, head to <comment>' . route('canvas.login') . '</comment> and update your credentials.');
                        $command->info('Have a great day!');
                    });
            })
        ;
    }

    public function bootingPackage()
    {
        $this->registerAuthDriver();
        $this->registerEvents();
        $this->configureRoutes();
    }

    /**
     * Register the package's authentication driver.
     *
     * @return void
     */
    private function registerAuthDriver(): void
    {
        $this->app->config->set('auth.providers.canvas_users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);

        $this->app->config->set('auth.guards.canvas', [
            'driver' => 'session',
            'provider' => 'canvas_users',
        ]);
    }

    /**
     * Register the events and listeners.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    private function registerEvents(): void
    {
        $mappings = [
            PostViewed::class => [
                CaptureView::class,
                CaptureVisit::class,
            ],
        ];

        $events = $this->app->make(Dispatcher::class);

        foreach ($mappings as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    private function configureRoutes(): void
    {
        Route::namespace('Canvas\Http\Controllers')
            ->middleware(config('canvas.middleware'))
            ->domain(config('canvas.domain'))
            ->prefix(config('canvas.path'))
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });
    }
}
