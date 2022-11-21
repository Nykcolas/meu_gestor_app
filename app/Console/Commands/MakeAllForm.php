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
        if ($this->confirm('Você deseja criar as colunas da tabela "'.$this->argument('name').'" agora?
        OBS.: Não recomendado em caso de uso de FK')) {
            $this->comment("Crie um objeto com as clunas desejadas, seguindo o senguinte padrão:

            {
                'nome_coluna':
                    {
                        'type':'varchar'
                        'default':null
                        'length':50
                    }
                }"
        );
            $array_colums = $this->ask("");
            dd(json_decode($array_colums));
        }
    }

    public function createANewLine()
    {
        // File::append('resources/views/layouts/tabsForm.blade.php', "\n<a class='dropdown-item' href='{{route(\"".$this->argument('name')."\")}}'>".ucfirst($this->argument('name'))."</a>");
        // File::replaceInFile("//Forms", "//Forms\nRoute::apiResource('".$this->argument('name')."', \App\Http\Controllers\\".ucfirst($this->argument('name'))."Controller::class);", 'routes/api.php');
        // File::replaceInFile("//Forms", "//Forms\n    Route::get('/".$this->argument('name')."', function() {return view('app\\".$this->argument('name')."');})->name('".$this->argument('name')."');", 'routes/web.php');
        // File::replaceInFile("//Form", "//Form\nVue.component('".ucfirst($this->argument('name'))."', require('./components/app/".ucfirst($this->argument('name')).".vue').default);", 'resources/js/app.js');
    }

    public function callCommandMake()
    {
        $commands = [
            'form'=>"Um novo arquivo controller para Form foi criado com sucesso",
            'formModel'=>"Um novo arquivo Model para Form foi criado com sucesso",
            'formUpdate'=>"Um novo arquivo Update para Form foi criado com sucesso",
            'formStore'=>"Um novo arquivo Store para Form foi criado com sucesso",
            'formMigration'=>"Um novo arquivo Migration para Form foi criado com sucesso",
            'formSeeder'=>"Um novo arquivo Seeder para Form foi criado com sucesso",
            'formFactory'=>"Um novo arquivo Factory para Form foi criado com sucesso",
            'formView'=>"Um novo arquivo View para Form foi criado com sucesso",
            'formVue'=>"Um novo arquivo Vue para Form foi criado com sucesso",
        ];

        foreach ($commands as $key => $value) {
            // Artisan::call('make:'.$key, ['name'=>$this->argument('name')]);
            $this->info($value);
        }
    }
}
