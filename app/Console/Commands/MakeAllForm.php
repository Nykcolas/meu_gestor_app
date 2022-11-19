<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem as File;

class MakeAllForm extends Command
{
    protected $signature = 'make:formAll {name}';
    protected $description = 'Create a new form controller, model, migration, seeder and requests';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->callCommandMake();
        $this->createANewLine();
    }

    public function createANewLine()
    {
        File::append('resources/views/layouts/tabsForm.blade.php', "\n<a class='dropdown-item' href='{{route(\"".$this->argument('name')."\")}}'>".ucfirst($this->argument('name'))."</a>");
        File::replaceInFile("//Forms", "//Forms\nRoute::apiResource('".$this->argument('name')."', \App\Http\Controllers\\".ucfirst($this->argument('name'))."Controller::class);", 'routes/api.php');
        File::replaceInFile("//Forms", "//Forms\n    Route::get('/".$this->argument('name')."', function() {return view('app\\".$this->argument('name')."');})->name('".$this->argument('name')."');", 'routes/web.php');
        File::replaceInFile("//Form", "//Form\nVue.component('".ucfirst($this->argument('name'))."', require('./components/app/".ucfirst($this->argument('name')).".vue').default);", 'resources/js/app.js');
    }

    public function callCommandMake()
    {
        $commands = [
            'form'=>"created Successfully a new Form controller",
            'formModel'=>"created Successfully a new Form Model",
            'formUpdate'=>"created Successfully a new Form Update",
            'formStore'=>"created Successfully a new Form Store",
            'formMigration'=>"created Successfully a new Form Migration",
            'formSeeder'=>"created Successfully a new Form Seeder",
            'formFactory'=>"created Successfully a new Form Factory",
            'formView'=>"created Successfully a new Form View",
            'formVue'=>"created Successfully a new Form Vue"
        ];

        foreach ($commands as $key => $value) {
            Artisan::call('make:'.$key, ['name'=>$this->argument('name')]);
            $this->info($value);
        }
    }
}
