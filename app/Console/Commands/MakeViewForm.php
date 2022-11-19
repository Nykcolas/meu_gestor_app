<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeViewForm extends GeneratorCommand
{
    protected $signature = 'make:formView {name}';

    protected $description = 'Create a new Form View';

    protected function replaceClass($stub, $name)
    {
        $stub = str_replace('{{classView}}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function getStub()
    {
        return  'stubs/make-form-view.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), $this->argument('name').".blade", $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $laravelPath = $this->laravel['path'];
        $laravelPath = Str::replaceFirst('\\app', '', $laravelPath);
        return $laravelPath.'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Resources\Views\App';
    }
}
