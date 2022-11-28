<?php

namespace ZhMead\LumenApiStarterGenerator\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class EloquentMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:eloquent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new eloquent';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Eloquent';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/eloquent.whole.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories\Eloquent';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
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
        $name = str_replace("App\Repositories\Eloquent", '', $name);

        $stub = str_replace(
            ['\\DummyClass'],

            [$name],
            $stub
        );
        return $this;
    }
}
