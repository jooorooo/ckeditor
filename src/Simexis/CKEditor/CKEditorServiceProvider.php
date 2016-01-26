<?php namespace Simexis\CKEditor;

use \Illuminate\Support\ServiceProvider;
use \Illuminate\Support\Facades\Config;

use \Braintree_Configuration;

class CKEditorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/ckeditor.php' => config_path('ckeditor.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../../public/ckeditor/' => public_path('vendor/simexis-ckeditor'),
        ], 'public');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/ckeditor.php', 'ckeditor'
        );
    }

    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
    public function register()
    {
		$this->app->singleton('ckeditor', function ($app) {
            return new CKEditor($app);
        });

    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('ckeditor');
	}

}
