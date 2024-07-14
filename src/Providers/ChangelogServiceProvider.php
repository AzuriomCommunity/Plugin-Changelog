<?php

namespace Azuriom\Plugin\Changelog\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;

class ChangelogServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        // $this->registerMiddlewares();

        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();

        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        // $this->registerUserNavigation();

        Permission::registerPermissions(['changelog.admin' => 'changelog::admin.permissions.admin']);
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array
     */
    protected function routeDescriptions()
    {
        return [
            'changelog.index' => trans('changelog::messages.title'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array
     */
    protected function adminNavigation()
    {
        return [
            'changelog' => [
                'name' => trans('changelog::admin.title'),
                'icon' => 'bi bi-list-check',
                'route' => 'changelog.admin.updates.index',
                'permission' => 'changelog.admin',
            ],
        ];
    }
}
