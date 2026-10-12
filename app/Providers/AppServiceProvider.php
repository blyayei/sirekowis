<?php

namespace App\Providers;

use App\Database\Query\Grammars\CompatibleMySqlGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $connection = DB::connection('mysql');

        $connection->setQueryGrammar(new CompatibleMySqlGrammar($connection));
    }
}
