<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Pluralizer;

class CrudController extends Command
{
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }
    protected $signature = 'make:CrudController {name}';

    protected $description = 'Generate a controller with CRUD methods';

    public function handle()
    {
        $segments = explode('/', $this->argument('name'));
        $segments = explode('\\', $this->argument('name'));
        $segments = array_map('ucfirst', $segments);

        $path = app_path('Http\\Controllers') . "\\" . implode("\\", $segments) . 'Controller.php';
        $this->makeDirectory(dirname($path));

        $contents = $this->getStubContents();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
        $this->call('make:request', ['name' => $this->argument('name') . 'StoreRequest']);
        $this->call('make:request', ['name' => $this->argument('name') . 'UpdateRequest']);

    }
    protected function getStubContents()
    {
        // dd($this->getNamespace(), $this->getModelNamespace(), $this->getRootNamespace(), $this->getClassName(), $this->getStoreRequest(), $this->getUpdateRequest(), $this->getModelName(), $this->getModelInstance());

        $stub = str_replace(
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ ModelNameSpace }}', '{{ class }}', '{{ StoreModelRequest }}', '{{ UpdateModelRequest }}',
                '{{ ModelClass }}', '{{ modelVariable }}', '{{ modelVariables }}', '{{ ModelServiceNameSpace }}'],
            [$this->getNamespace(), $this->getRootNamespace(), $this->getModelNamespace(), $this->getClassName(), $this->getStoreRequest(), $this->getUpdateRequest(),
                $this->getModel(), $this->getModelVariable(), $this->getModelVariables(), $this->getModelServiceNameSpace()],
            $stub = File::get(base_path('stubs\crudController.stub'))
        );
        return $stub;
    }
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    protected function getNamespace()
    {
        //$name=company/companyController
        $segments = explode('/', $this->argument('name')); //$segments =['company','companyController']
        $segments = explode('\\', $this->argument('name')); //$segments =['company','companyController']
        $segments = array_map('ucfirst', $segments); //$segments =['Company','CompanyController']
        array_pop(($segments)); // Remove the last segment (controller name) //$segments =['Company']
        if (count($segments) > 0) {
            return 'App\\Http\\Controllers\\' . implode('\\', $segments);
        }
        return 'App\\Http\\Controllers' . implode('\\', $segments);

        // dd( 'App\\Http\\Controllers\\' .implode('\\', $segments)); //   App\\Http\\Controllers\\Company
    }

    protected function getModelServiceNameSpace()
    {
        $segments = explode('/', $this->argument('name'));
        $segments = explode('\\', $this->argument('name'));
        $segments = array_map('ucfirst', $segments);
        $segments = str_replace(['Controller', 'controller'], ['', ''], $segments);
        array_pop($segments);
        if (count($segments) > 0) {
            return 'App\\Services\\' . implode('\\', $segments) . "\\" . $this->getModelName() . "Service";
        }
        return "App\\Services" . implode("\\", $segments) . "\\" . $this->getModelName() . "Service";
    }
    protected function getRootNamespace()
    {
        return app()->getNamespace();
    }
    protected function getModel()
    {
        return $this->getModelName();
    }
    protected function getModelVariables()
    {
        // dd (Pluralizer::plural(lcfirst($this->getModelName())));
        return Pluralizer::plural(lcfirst($this->getModelName()));
    }

    protected function getRequestsNamespace()
    {
        return 'App\\Http\\Requests';
    }

    protected function getClassName()
    {

        $segments = explode('/', $this->argument('name'));
        $segments = explode('\\', $this->argument('name'));
        $segments = array_map('ucfirst', $segments);
        return array_pop($segments);
    }

    protected function getStoreRequest()
    {
        return $this->getModelName() . 'StoreRequest';
    }

    protected function getUpdateRequest()
    {
        return $this->getModelName() . 'UpdateRequest';
    }

    protected function getModelName()
    {
        $segments = explode('/', $this->argument('name'));
        $segments = explode('\\', $this->argument('name'));
        $segments = array_map('ucfirst', $segments);
        $segments = str_replace(['Controller', 'controller'], ['', ''], $segments);
        return end($segments);
    }

    // protected function getModelInstance($name)
    // {
    //     $segments = explode('/', $name);
    //     $segments = array_map('ucfirst', $segments);
    //     return end($segments);
    // }

    protected function getModelVariable()
    {
        return lcfirst($this->getModelName());
    }
    protected function getModelNamespace()
    {
        $segments = explode('/', $this->argument("name"));
        $segments = explode('\\', $this->argument("name"));
        $segments = array_map('ucfirst', $segments);
        $segments = str_replace(['Controller', 'controller'], ['', ''], $segments);
        return "App\\Models" . "\\" . implode("\\", $segments);
    }

}
