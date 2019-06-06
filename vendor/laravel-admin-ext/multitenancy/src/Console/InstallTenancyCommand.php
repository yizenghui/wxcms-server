<?php

namespace Encore\Admin\Multitenancy\Console;

use Encore\Admin\Console\InstallCommand;

class InstallTenancyCommand extends InstallCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:install:tenancy {name=tenancy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a new tenancy of laravel-admin';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->overrideConfig();

        parent::handle();
    }

    /**
     * Create tenancy tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $path = $this->createMigration();

        $this->call('migrate', ['--path' => 'database/'.dirname($path)]);

        $userModel = config('admin.database.users_model');

        if ($userModel::count() == 0) {
            $this->call('db:seed', ['--class' => \Encore\Admin\Auth\Database\AdminTablesSeeder::class]);
        }
    }

    /**
     * Override admin config with tenancy config.
     *
     * @return void
     */
    protected function overrideConfig()
    {
        $name = $this->argument('name');

        $config = require config("admin.extensions.multitenancy.$name");

        config(['admin' => $config]);

        config(array_dot(config('admin.auth', []), 'auth.'));
    }

    /**
     * @return string
     */
    protected function createMigration()
    {
        $name = $this->argument('name');

        $path = sprintf('migrations/%s/%s_create_%s_tables.php', $name, date('Y_m_d_His'), $name);

        $migration = __DIR__.'/../../database/migrations/create_tenancy_tables.php';

        $contents = str_replace(['tenancy', 'Tenancy'], [$name, ucfirst($name)], file_get_contents($migration));

        $file = database_path($path);

        $this->laravel['files']->makeDirectory(dirname($file), 0755, true, true);

        $this->laravel['files']->put($file, $contents);

        $this->line('<info>Created Migration:</info> '.pathinfo($file, PATHINFO_FILENAME));

        return $path;
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = $this->directory.'/routes.php';

        $contents = <<<PHP
<?php

Route::get('/', 'HomeController@index');

PHP;
        $this->laravel['files']->put($file, str_replace('DummyNamespace', config('admin.route.namespace'), $contents));

        $this->line('<info>Routes file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Get stub contents.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        $path = base_path("vendor/encore/laravel-admin/src/Console/stubs/$name.stub");

        return $this->laravel['files']->get($path);
    }
}