<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

class CrudOperation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:CrudOperation {modelName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating CRUD operations for a model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('modelName');

        // Call commands to generate files
        $this->call('make:model', ['name' => $modelName]);
        $this->call('make:CrudController', ['name' => $modelName . 'Controller']);
        $this->call('make:interface', ['interfaceName' => $modelName]);
        $this->call("make:repository", ['repositoryName' => $modelName]);
        $this->call('make:CrudService', ['name' => $modelName ]);
        $this->call('make:migration', ['name' => "create_" . $this->generateMigrationName() . "_table"]);
        $this->call('make:seeder', ['name' => $modelName . 'TableSeeder']);
        $this->call('make:factory', ['name' => $modelName . 'Factory']);
        $this->call('make:CrudRequest', ['name' => $modelName . 'Request']);
        $this->call('make:view', ['name' => $modelName]);

        // Generate route definitions
        $this->generateRouteDefinitions($modelName);

        $this->info("Crud operations created successfully for the {$modelName} model");
        return Command::SUCCESS;
    }

    /**
     * Generate route definitions for the CRUD operations.
     *
     * @param string $modelName
     * @return void
     */
/**
 * Generate route definitions for the CRUD operations.
 *
 * @param string $modelName The name of the model.
 * @return void
 */
    protected function generateRouteDefinitions($modelName)
    {
        // Get the path to the api.php route file
        $routeFile = base_path('routes/api.php');

        // Read the content of the existing route file
        $routeDefinitions = File::get($routeFile);

        // Generate the controller name based on the model name
        $controllerName = $modelName . 'Controller';

        // Append route definitions for CRUD operations
        //append  it means to add some route definitions to the existing route file
        $modelNames = Pluralizer::plural($modelName);
        $routeDefinitions .= "Route::prefix('auth')->group(function () {
        Route::controller({$controllerName}::class)->prefix('review')->group(function () {
        Route::get('/{$modelNames}',  'index');
        Route::post('store/{$modelName}',  'store');
        Route::get('show/{$modelName}/{id}',  'show');
        Route::put('update/{$modelName}/{id}',  'update');
        Route::delete('delete/{$modelName}/{id}',  'destroy');
        Route::delete('delete/{$modelNames}',  'deleteAll');
        });
        });
    ";

        // Write the updated route definitions back to the route file
        File::put($routeFile, $routeDefinitions);
    }
    protected function generateMigrationName()
    {
        $segments = explode('\\', $this->argument('modelName'));
        $segments = explode('/', $this->argument('modelName'));
        $segments = array_map("lcfirst", $segments);
        $modelName = end($segments);
        return ((Pluralizer::plural($modelName)));
    }

}
