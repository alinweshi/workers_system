<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Service extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {modelName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating service for a model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('modelName');
        $modelNameSpace = 'App\Models\\' . $modelName;
        $serviceName = 'App\Services\\' . $modelName . 'Service';
        $modelVariableName = lcfirst($modelName);
        $modelVariableNamePlural = $modelVariableName . 's';
        $modelVariableNamePluralLower = strtolower($modelVariableNamePlural);
        $modelVariableNamePluralUpper = strtoupper($modelVariableNamePlural);
        $modelVariableNameLower = strtolower($modelVariableName);
        $modelVariableNameUpper = strtoupper($modelVariableName);
        $stub = file_get_contents(__DIR__ . '/../../stubs/service.stub');
        $stub = str_replace(
            ['DummyModelNameSpace', 'DummyModelName', 'DummyModelVariableName', 'DummyModelVariableNamePlural', 'DummyModelVariableNamePluralLower', 'DummyModelVariableNamePluralUpper', 'DummyModelVariableNameLower', 'DummyModelVariableNameUpper'],
            [$modelNameSpace, $modelName, $modelVariableName, $modelVariableNamePlural, $modelVariableNamePluralLower, $modelVariableNamePluralUpper, $modelVariableNameLower, $modelVariableNameUpper], $stub);
        file_put_contents(app_path('Services/' . $modelName . 'Service.php'), $stub);
        $this->info('Service created successfully.');
    }
}
