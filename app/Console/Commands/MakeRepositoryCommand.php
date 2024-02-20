<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeRepositoryCommand extends Command
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
    protected $signature = 'make:repository {repositoryName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating repository class';
    /**
     * Return the Singular Capitalize Name
     * @param $repositoryName
     * @return string
     */
    public function getSingularClassName()
    {
        $segments = explode("/", $this->argument("repositoryName"));
        $segments = array_map('ucfirst', $segments);
        return $segments = implode("\\", $segments);
    }
    public function getPluralClassName($repositoryName)
    {
        return ucwords(Pluralizer::plural($repositoryName));
    }
    protected function getClassName()
    {
        $segments = explode('/', $this->argument('repositoryName'));
        $segments = array_map('ucfirst', $segments);
        return end($segments);
    }
    protected function getModelPath()
    {
        $segments = explode('/', $this->argument('repositoryName'));
        $segments = array_map('ucfirst', $segments);
        return 'App\\Models\\' . implode("\\", $segments);

    }
    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath()
    {
        return $stubPath = __DIR__ . '/../../../stubs/repository.stub';
    }
    protected function getNamespace()
    {
        $segments = explode('/', $this->argument('repositoryName'));
        $segments = array_map('ucfirst', $segments);
        array_pop($segments);
        if (count($segments) > 0) {
            return "App\\Repositories\\" . implode("\\", $segments);
        }
        return "App\\Repositories" . implode("\\", $segments);
    }
    protected function getInterfaceNamespace()
    {
        $segments = explode('/', $this->argument('repositoryName'));
        $segments = array_map('ucfirst', $segments);
        array_pop($segments);
        if (count($segments) > 0) {
            return "App\\Interfaces\\" . implode("\\", $segments) . "\\" .$this->getClassName() . "RepositoryInterface";
        }
        return "App\\Interfaces" . implode("\\", $segments) . "\\". $this->getClassName() . "RepositoryInterface";
    }
    public function getModelNames()
    {
        $segments = explode('/', $this->argument("repositoryName"));
        $segments = array_map('ucfirst', $segments);
        end($segments);
        return $segments = Pluralizer::plural(end($segments));
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
            'CLASS_NAME' => $className = $this->getClassName() . "Repository",
            "INTERFACE_NAME" => $this->getClassName() . "RepositoryInterface",
            "MODEL_NAME" => $this->getClassName(),
            "MODEL_NAMES" => $this->getModelNames(),
            "MODEL_PATH" => $this->getModelPath(),
            "INTERFACE_NAMESPACE" => $this->getInterfaceNamespace(),
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
    public function getSourceFile() //using this function instead of getStubContents to prevent using args
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
        return base_path('App\\Repositories') . '\\' . $this->getSingularClassName() . 'Repository.php';
        // return base_path('App\\Interfaces') . '\\' . $this->getSingularClassName($this->argument($interfaceName)) . 'Interface.php';
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
        $interfaceName = $this->argument('repositoryName');

        $path = $this->getSourceFilePath();
        $this->makeDirectory(dirname($path));
        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }

    }
}
