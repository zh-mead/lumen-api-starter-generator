<?php

namespace ZhMead\LumenApiStarterGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

abstract class GeneratorCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new controller creator command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    abstract protected function getStub();

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type . ' created successfully.');
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);
        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     *
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        //controller special deal
        if ($this->type == 'Controller') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Controller' . '.php';
        }
        if ($this->type == 'Service') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Service' . '.php';
        }
        if ($this->type == 'Resource') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Resource' . '.php';
        }
        if ($this->type == 'Criteria') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Criteria' . '.php';
        }
        if ($this->type == 'Eloquent') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'RepositoryEloquent' . '.php';
        }
        if ($this->type == 'Presenter') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Presenter' . '.php';
        }
        if ($this->type == 'Transformer') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Transformer' . '.php';
        }
        if ($this->type == 'Repository') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Repository' . '.php';
        }
        if ($this->type == 'Validator') {
            return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . 'Validator' . '.php';
        }
        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceOther($stub, $name)->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],

            [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel()],
            $stub
        );

        return $this;
    }

    protected function replaceOther(&$stub, $name)
    {
        return $this;
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $dummyClass = str_replace($this->getNamespace($name) . '\\', '', $name);
        return str_replace(['DummyClass', 'DummyLowercaseClass'], [$dummyClass, lcfirst($dummyClass)], $stub);
    }

    /**
     * Alphabetically sorts the imports for the given stub.
     *
     * @param string $stub
     *
     * @return string
     */
    protected function sortImports($stub)
    {
        if (preg_match('/(?P<imports>(?:use [^;]+;$\n?)+)/m', $stub, $match)) {
            $imports = explode("\n", trim($match['imports']));

            sort($imports);

            return str_replace(trim($match['imports']), implode("\n", $imports), $stub);
        }

        return $stub;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Get the model for the default guard's user provider.
     *
     * @return string|null
     */
    protected function userProviderModel()
    {
        $guard = config('auth.defaults.guard');

        $provider = config("auth.guards.{$guard}.provider");

        return config("auth.providers.{$provider}.model");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
