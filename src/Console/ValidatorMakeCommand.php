<?php

namespace ZhMead\LumenApiStarterGenerator\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ValidatorMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:validator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new validator';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validator';

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
        return __DIR__. '/stubs/validator.whole.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories\Validators';
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
        $name = str_replace("App\Repositories\Validators", '', $name);
        $stub = str_replace(
            ['\\DummyClass'],

            [$name],
            $stub
        );
        return $this;
    }
}
