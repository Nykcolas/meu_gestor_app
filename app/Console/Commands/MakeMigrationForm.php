<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeMigrationForm extends GeneratorCommand
{
    protected $signature = 'make:formMigration {name}';

    protected $description = 'Create a new Form Migration';

    protected function replaceClass($stub, $name)
    {
        $stub = $this->replaceClassItems($stub);
        $stub = str_replace('{{classMigration}}', ucfirst($this->argument('name')), $stub);
        return $stub;
    }

    protected function replaceClassItems($stub) {
        return str_replace('{{table}}', $this->argument('name'), $stub);
    }

    protected function getStub()
    {
        return  'stubs/make-form-migration.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), date("Y_m_d_his_")."create_".$this->argument('name')."_table", $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $laravelPath = $this->laravel['path'];
        $laravelPath = Str::replaceFirst('\\app', '', $laravelPath);
        return $laravelPath.'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        
      return $rootNamespace . '\Database\Migrations';
    }
}
