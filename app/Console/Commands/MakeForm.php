<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeForm extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:form {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Form';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    protected function replaceClass($stub, $name)
    {
        $stub = $this->replaceClassItems($stub);
        $stub = str_replace('{{ classController }}', ucfirst($this->argument('name'))."Controller", $stub);
        return $stub;
    }

    protected function replaceClassItems($stub) {
        return str_replace('{{nomeClass}}', ucfirst($this->argument('name')), $stub);
    }


    protected function getStub()
    {
        return  'stubs/make-form.stub';
    }

    protected function getPath($name)
    {
        $name = str_replace($this->argument('name'), ucfirst($this->argument('name'))."Controller", $name);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Http\Controllers';
    }
}
