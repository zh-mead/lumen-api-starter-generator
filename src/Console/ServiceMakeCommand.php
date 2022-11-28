<?php

namespace ZhMead\LumenApiStarterGenerator\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = $this->option('all')
            ? '/stubs/service.whole.stub'
            : '/stubs/service.stub';

        return __DIR__ . $stub;
    }

    /**
     * Determine if the command is generating a resource collection.
     *
     * @return bool
     */
    protected function collection()
    {
        return $this->option('collection') ||
               Str::endsWith($this->argument('name'), 'Collection');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Create a resource collection.'],
        ];
    }

    /**
     * 替换其他的参数
     * @param $stub
     * @param $name
     * @return $this|ControllerMakeCommand
     */
    protected function replaceOther(&$stub, $name)
    {
        $name = str_replace("App\Services", '', $name);

        $stub = str_replace(
            ['\\DummyClass'],

            [$name],
            $stub
        );
        return $this;
    }
}
