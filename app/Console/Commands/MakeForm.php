<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

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
        $stub = parent::replaceClass($stub, $name);
        return str_replace('FormControllerGeneric', ucfirst($this->argument('name'))."Controller", $stub);
    }

    protected function getStub()
    {
      return  'stubs/make-form.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
      return $rootNamespace . '\Http\Controllers';
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
    //     echo "chegou";
    // }
}
