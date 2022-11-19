<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeSeederForm extends GeneratorCommand
{
    protected $signature = 'make:formSeeder {name}';

    protected $description = 'Create a new Form Seeder';

    protected function replaceClass($stub, $name)
    {
        $stub = str_replace('{{classSeeder}}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function getStub()
    {
        return  'stubs/make-form-seeder.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), ucfirst($this->argument('name'))."Seeder", $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $laravelPath = $this->laravel['path'];
        $laravelPath = Str::replaceFirst('\\app', '', $laravelPath);
        return $laravelPath.'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        
      return $rootNamespace . '\Database\Seeders';
    }
}
