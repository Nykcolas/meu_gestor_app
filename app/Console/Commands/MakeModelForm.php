<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeModelForm extends GeneratorCommand
{
    protected $signature = 'make:formModel {name}';

    protected $description = 'Create a new Form Model';

    protected function replaceClass($stub, $name)
    {
        // $stub = parent::replaceClass($stub, $name);
        $stub = str_replace('{{ classModel }}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function getStub()
    {
        return  'stubs/make-form-model.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), ucfirst($this->argument('name')), $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Models';
    }
}
