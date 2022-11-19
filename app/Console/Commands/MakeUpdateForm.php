<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeUpdateForm extends GeneratorCommand
{
    protected $signature = 'make:formUpdate {name}';

    protected $description = 'Create a new Form Update request';

    protected function replaceClass($stub, $name)
    {
        $stub = str_replace('{{ classUpdate }}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function getStub()
    {
        return  'stubs/make-form-update-request.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), "Update".ucfirst($this->argument('name'))."Request", $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Http\Requests';
    }
}
