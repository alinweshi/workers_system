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
        $this->call('make:controller', ['name' => $modelName . 'Controller']);
        $this->call('make:interface', ['interfaceName' => $modelName]);
        $this->call("make:repository", ['repositoryName' => $modelName]);
        $this->call('make:resource', ['name' => $modelName . 'Resource']);
        $this->call('make:migration', ['name' => "create_" . strtolower(Str::plural($modelName)) . "_table"]);
        $this->call('make:seeder', ['name' => $modelName . 'TableSeeder']);
        $this->call('make:factory', ['name' => $modelName . 'Factory']);
        $this->call('make:request', ['name' => $modelName . 'Request']);
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
        $routeDefinitions .= "
        Route::get('/{$modelNames}', [{$controllerName}::class, 'index']);
        Route::post('/{$modelNames}', [{$controllerName}::class, 'store']);
        Route::get('/{$modelNames}/{id}', [{$controllerName}::class, 'show']);
        Route::put('/{$modelNames}/{id}', [{$controllerName}::class, 'update']);
        Route::delete('/{$modelNames}/{id}', [{$controllerName}::class, 'destroy']);
    ";

        // Write the updated route definitions back to the route file
        File::put($routeFile, $routeDefinitions);
    }

}
