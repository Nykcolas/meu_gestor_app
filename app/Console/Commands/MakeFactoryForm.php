<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeFactoryForm extends GeneratorCommand
{
    protected $signature = 'make:formFactory {name}';

    protected $description = 'Create a new Form Factory';

    protected function replaceClass($stub, $name)
    {
        $stub = str_replace('{{classFactory}}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function getStub()
    {
        return  'stubs/make-form-factories.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), ucfirst($this->argument('name'))."Factory", $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $laravelPath = $this->laravel['path'];
        $laravelPath = Str::replaceFirst('\\app', '', $laravelPath);
        return $laravelPath.'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        
      return $rootNamespace . '\Database\Factories';
    }
}
