<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeInterfaceCommand extends Command
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
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {interfaceName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creating interface command';

    /**
     * Return the Singular Capitalize Name
     * @param $interfaceName
     * @return string
     */
    public function getModelName()
    {
        $segments = explode('/', $this->argument("interfaceName"));
        $segments = array_map('ucfirst', $segments);
        return end($segments);
    }
    public function getModelNames()
    {
        $segments = explode('/', $this->argument("interfaceName"));
        $segments = array_map('ucfirst', $segments);
        return $segments = Pluralizer::plural(end($segments));
    }

    public function getPluralClassName()
    {
        return ucwords(Pluralizer::plural($this->argument("interfaceName")));
    }
    public function getSingularClassName()
    {
        $segments = explode("/", $this->argument("interfaceName"));
        $segments = array_map('ucfirst', $segments);
        $segments = implode("\\", $segments);
        return $segments;
    }
    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath()
    {
        return $stubPath = __DIR__ . '/../../../stubs/interface.stub';
    }
    protected function getClassName()
    {
        $segments = explode('/', $this->argument("interfaceName"));
        $segments = array_map('ucfirst', $segments);
        return end($segments);
    }
    protected function getModelInstance()
    {
        $segments = explode('/', $this->argument("interfaceName"));
        $segments = array_map('ucfirst', $segments);
        $segments = end($segments);
        return (lcfirst($segments));
    }
    protected function getNamespace()
    {
        $segments = explode('/', $this->argument('interfaceName'));
        $segments = array_map('ucfirst', $segments);
        array_pop($segments);
        if(count($segments) > 0){

            return 'App\\Interfaces\\' . implode("\\", $segments);
        }
        return 'App\\Interfaces' . implode("\\", $segments);
    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @return array
     *
     */
    public function getStubVariables()
    {

        return $stubVariables = [
            'NAMESPACE' => $this->getNamespace(),
            'CLASS_NAME' => $className = $this->getClassName(),
            "modelInstance" => $this->getModelInstance(),
            "MODEL_NAME" => $this->getModelName(),
            "MODEL_NAMES" => $this->getModelNames(),

        ];
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stubPath, $stubVariables = [])
    {
        // Read the contents of the stub file
        $contents = file_get_contents($stubPath);

        // Iterate over each variable in the $stubVariables array
        foreach ($stubVariables as $search => $replace) {
            // Replace each occurrence of the variable placeholder in the stub content with its corresponding value
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }

        // Return the modified stub content with variable placeholders replaced
        return $contents;
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return base_path('App\\Interfaces') . '\\' . $this->getSingularClassName() . 'RepositoryInterface.php';
    }
    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interfaceName = $this->argument('interfaceName');

        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));
        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }

        // $interfaceName = $this->argument('interfaceName');
        // $path = app_path('Interfaces/' . $interfaceName . 'Interface.php');
        // $stub = file_get_contents(__DIR__ . '/stubs/interface.stub');
        // $stub = str_replace('DummyInterface', $interfaceName . 'Interface', $stub);
        // file_put_contents($path, $stub);
        // $this->info('Interface created successfully.');
    }
}
