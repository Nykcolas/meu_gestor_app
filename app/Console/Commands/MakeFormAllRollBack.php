<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Support\Facades\Artisan;

class MakeFormAllRollBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'form:rollback {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files created in form:all';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('Isso vai reverter o codigo gerado pelo comando "form:all '.$this->argument('name').'" deseja continuar?');
        if ($this->confirm('')) {
            $this->deleteLines();
            $this->deleteFiles();
        }
    }

    public function deleteLines()
    {
        File::replaceInFile("\nRoute::apiResource('".$this->argument('name')."', \App\Http\Controllers\\".ucfirst($this->argument('name'))."Controller::class);", "", 'routes/api.php');
        File::replaceInFile("\n    Route::get('/".$this->argument('name')."', function() {return view('app\\".$this->argument('name')."');})->name('".$this->argument('name')."');", "", 'routes/web.php');
        File::replaceInFile("\nVue.component('".ucfirst($this->argument('name'))."', require('./components/app/".ucfirst($this->argument('name')).".vue').default);", "", 'resources/js/app.js');
        File::replaceInFile("\n<a class='dropdown-item' href='{{route(\"".$this->argument('name')."\")}}'>".ucfirst($this->argument('name'))."</a>", "", 'resources/views/layouts/tabsForm.blade.php');
        $this->warn("Uma linha do \"arquivo resources/views/layouts/tabsForm.blade.php\" Foi removida");
        $this->warn("Uma linha do \"arquivo routes/api.php\" Foi removida");
        $this->warn("Uma linha do \"arquivo routes/web.php\" Foi removida");
        $this->warn("Uma linha do \"arquivo resources/views/layouts/tabsForm.blade.php\" Foi removida");
    }

    public function deleteFiles()
    {
        $array_migrations = File::files('database/migrations/');
        $file_name = "";
        foreach ($array_migrations as $key => $value) {
            if(strpos($value->getFilename(), "create_".$this->argument('name')."_table"))
                $file_name = $value->getFilename();
        }

        File::delete('app/Http/Controllers/'.ucfirst($this->argument('name'))."Controller.php");
        File::delete('app/Models/'.ucfirst($this->argument('name')).".php");
        File::delete('app/Http/Requests/Store'.ucfirst($this->argument('name'))."Request.php");
        File::delete('app/Http/Requests/Update'.ucfirst($this->argument('name'))."Request.php");
        File::delete('database/seeders/'.ucfirst($this->argument('name'))."Seeder.php");
        File::delete('database/factories/'.ucfirst($this->argument('name'))."Factory.php");
        File::delete('resources/views/app/'.$this->argument('name').".blade.php");
        File::delete('resources/js/components/app/'.ucfirst($this->argument('name')).".vue");
        Artisan::call("migrate:rollback --path=database/migrations/$file_name");
        File::delete("database/migrations/$file_name");
        $this->warn("O arquivo \"app/Http/Controllers/".ucfirst($this->argument('name'))."Controller.php\" Foi Deletado");
        $this->warn("O arquivo \"app/Models/".ucfirst($this->argument('name')).".php\" Foi Deletado");
        $this->warn("O arquivo \"app/Http/Requests/Store".ucfirst($this->argument('name'))."Request.php\" Foi Deletado");
        $this->warn("O arquivo \"app/Http/Requests/Update".ucfirst($this->argument('name'))."Request.php\" Foi Deletado");
        $this->warn("O arquivo \"database/seeders/".ucfirst($this->argument('name'))."Seeder.php\" Foi Deletado");
        $this->warn("O arquivo \"database/factories/".ucfirst($this->argument('name'))."Factory.php\" Foi Deletado");
        $this->warn("O arquivo \"resources/views/app/".$this->argument('name').".blade.php\" Foi Deletado");
        $this->warn("O arquivo \"resources/js/components/app/".ucfirst($this->argument('name')).".vue\" Foi Deletado");
        $this->warn("O arquivo \"database/migrations/$file_name\" Foi Deletado");
    }
}
