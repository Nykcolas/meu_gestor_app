<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeVueForm extends GeneratorCommand
{
    protected $signature = 'form:vue {name}';

    protected $description = 'Create a new Form Vue';

    protected function replaceClass($stub, $name)
    {
        $stub = str_replace('{{classVue}}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function getStub()
    {
        return  'stubs/make-form-vue.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), ucfirst($this->argument('name')), $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $laravelPath = $this->laravel['path'];
        $laravelPath = Str::replaceFirst('\\app', '', $laravelPath);
        return $laravelPath.'/'.str_replace('\\', '/', $name).'.vue';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Resources\Js\Components\App';
    }
}
