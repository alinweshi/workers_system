<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CrudService extends Command
{
    protected $files;
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:CrudService {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creating crud service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // dd($this->getStubPath());
        $name = $this->argument('name');
        $path = $this->getSourceFilePath($name);
        $this->makeDirectory(dirname($path));
        $contents = $this->getSourceFile($name);

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->call('make:repository', ['repositoryName' => $name]);
            $this->call('make:interface', ['interfaceName' => $name]);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }

    }

    protected function getParentDirectory($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        $segments = implode('\\', $segments);

        return ($segments);

    }
    protected function getStubPath()
    {
        return $stubPath = __DIR__ . '/../../../stubs/CrudService.stub';
    }
    protected function getStubContents()
    {
        return $contents = file_get_contents($this->getStubPath());

    }

    protected function getSourceFile($name)
    {
        $contents = str_replace(['{{ pluralModelClass }}','{{ namespace }}', '{{ modelClass }}', '{{ modelRepositoryRoot }}', '{{ class }}', '{{ modelRepository}}', "{{ modelInstance }}"],
            [$this->getPluralModelClass($name),$this->getNamespace($name), $this->getModelClass($name), $this->modelRepositoryRoot($name), $this->getModelClass($name), $this->getModelRepositoryName($name), $this->getModelInstance($name)],
            $this->getStubContents());
        return $contents;
    }
    //to get {{ namespace }}2
    protected function getNamespace($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        array_pop($segments);
        if (count($segments) > 0) {

            return "App\\Services" . "\\" . implode('\\', $segments);
        }
        return "App\\Services" . implode('\\', $segments);

    }
    protected function getModelClass($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        return end($segments);
    }
    protected function getPluralModelClass($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        return pluralizer::plural(end($segments));
    }
    protected function getModelInstance($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        $segments = end($segments);
        return (lcfirst($segments));
    }
    protected function getModelRepositoryName($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        return end($segments);
    }
    protected function getModelRepository($name)
    {
        $segments = explode('/', $name);
        $segments = array_map('ucfirst', $segments);
        return implode("\\", $segments);
    }
    protected function modelRepositoryRoot($name)
    {
        return "App\\Repositories\\" . $this->getModelRepository($name) . "Repository";
    }
    protected function getSourceFilePath($name)
    {
        return app_path('Services' . "\\" . $this->getParentDirectory($name) . 'Service.php');
    }
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

}
