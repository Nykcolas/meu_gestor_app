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

            [
                '".$this->argument('name')."_coluna'=>
                    [
                        'type'=>'varchar'
                        'default'=>null
                        'length'=>50
                    ]
            ]"
            );
            $str_colums = $this->ask("");
            $array_colums = eval("return $str_colums;");
            $this->MakeColumsMigrate($array_colums);
        }

    }

    public function MakeColumsMigrate(array $array_colums)
    {
        $array_types = ['varchar'=>'string',
                        'date'=>'date',
                        'decimal'=>'decimal',
                        'int'=>'integer',
                        'datetime'=>'dateTime'];
        $command = "            //colums\n";
        foreach ($array_colums as $name_colum => $attrubetes) {
            $length = isset($attrubetes["length"]) ? ", $attrubetes[length]" : '';
            $default = '';
            if (isset($attrubetes["default"])) {
                if ($attrubetes["default"] == null) {
                    $default = '->nullable()';
                } else {
                    $default = "->default(".$attrubetes["default"].")";
                }
            }
            $command .= '            $table->'.($array_types[$attrubetes['type']]??'string').'("'.$name_colum.'"'.$length.')'.$default.";\n";
        }
        $array_migrations = File::files('database/migrations/');
        end($array_migrations);
        $key = key($array_migrations);
        File::replaceInFile("            //colums", $command, "database/migrations/".$array_migrations[$key]->getFilename());
        $this->info("Novas colunas foram criadas em database/migrations/".$array_migrations[$key]->getFilename());
        $this->MakeInputsColumsVue($array_colums);
    }

    public function MakeInputsColumsVue(array $array_colums)
    {
        $commandInput = "            <!-- inputs -->\n";
        $commandColums = "                //colums\n";
        foreach ($array_colums as $name_colum => $attrubetes) {
            $type = $attrubetes['type'] == "date" ? 'date': 'text';
            $mask = '';
            switch ($attrubetes['type']) {
                case 'decimal':
                    $mask = 'mascara="decimal"';
                    break;

                case 'int':
                    $mask = 'mascara="inteiro"';
                    break;
                
                default:
                    $mask = '';
                    break;
            }
            $label = ucwords(str_replace('_', ' ', $name_colum));
            $commandInput .= "            <inputComponent label=\"$label\" name=\"$name_colum\" $mask type=\"$type\"></inputComponent>\n";
            $commandColums .= "                $name_colum:'$label',\n";
        }

        File::replaceInFile("            <!-- inputs -->", $commandInput, 'resources/js/components/app/'.ucfirst($this->argument('name')).'.vue');
        File::replaceInFile("                //colums", $commandColums, 'resources/js/components/app/'.ucfirst($this->argument('name')).'.vue');
        $this->info("Novos inputs foram criadas em resources/js/components/app/".ucfirst($this->argument('name')).".vue");
        $this->MakeRulesUpdateStoreRequests($array_colums);
    }

    public function MakeRulesUpdateStoreRequests(array $array_colums)
    {
        $commandColums = "            //rules\n";
        foreach ($array_colums as $name_colum => $attrubetes) {
            $rules = "";
            if (!array_key_exists('default', $attrubetes)) {
                $rules .= "required";
            }

            switch ($attrubetes['type']) {
                case 'decimal':
                    $rules .= !array_key_exists('default', $attrubetes) ? "|numeric" : "numeric";
                    break;
                case 'int':
                    $rules .= 'numeric';
                    break;
                case 'date':
                    $rules .= 'date';
                    break;
                case 'varchar':
                    $rules .= '';
                    break;
                
                default:
                    $rules .= '';
                    break;
            }
            
            $commandColums .= "            '$name_colum' => '$rules',\n";
        }

        File::replaceInFile("            //rules", $commandColums, "app/Http/Requests/Store".ucfirst($this->argument('name'))."Request.php");
        File::replaceInFile("            //rules", $commandColums, "app/Http/Requests/Update".ucfirst($this->argument('name'))."Request.php");
        $this->info("Novas regras foram criadas em app/Http/Requests/Update".ucfirst($this->argument('name'))."Request.php");
        $this->info("Novas regras foram criadas em app/Http/Requests/Store".ucfirst($this->argument('name'))."Request.php");
        Artisan::call('migrate');
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
            Artisan::call('make:'.$key, ['name'=>$this->argument('name')]);
            $this->info($value);
        }
    }
}
