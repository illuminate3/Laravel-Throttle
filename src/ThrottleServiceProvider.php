<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * This is the throttle service provider class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class ThrottleServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/throttle.php');

        $this->publishes([$source => config_path('throttle.php')]);

        $this->mergeConfigFrom($source, 'throttle');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory($this->app);
        $this->registerTransformer($this->app);
        $this->registerThrottle($this->app);
    }

    /**
     * Register the factory class.
     *
     * @param Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerFactory(Application $app)
    {
        $app->singleton('throttle.factory', function (Application $app) {
            $cache = $app->cache->driver($app->config->get('throttle.driver'));

            return new Factories\CacheFactory($cache);
        });

        $app->alias('throttle.factory', 'GrahamCampbell\Throttle\Factories\FactoryInterface');
    }

    /**
     * Register the transformer class.
     *
     * @param Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerTransformer(Application $app)
    {
        $app->singleton('throttle.transformer', function () {
            return new Transformers\TransformerFactory();
        });

        $app->alias('throttle.transformer', 'GrahamCampbell\Throttle\Transformers\TransformerFactory');
    }

    /**
     * Register the throttle class.
     *
     * @param Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerThrottle(Application $app)
    {
        $app->singleton('throttle', function ($app) {
            $factory = $app['throttle.factory'];
            $transformer = $app['throttle.transformer'];

            return new Throttle($factory, $transformer);
        });

        $app->alias('throttle', 'GrahamCampbell\Throttle\Throttle');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'throttle',
            'throttle.factory',
            'throttle.transformer',
        ];
    }
}
