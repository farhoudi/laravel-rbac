<?php

namespace Farhoudi\Rbac;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider {

    public function register() {
        //
    }

    public function boot() {
        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'migrations');
    }

}