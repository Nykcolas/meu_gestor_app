<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem as File;

class MakeAllForm extends Command
{
    protected $signature = 'form:all {name}';
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
                        'type'=>'varchar',
                        'default'=>null,
                        'length'=>50,
                        'unique'=>true
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
                        'datetime'=>'dateTime',
                        'boolean'=>'boolean'
                    ];
        $command = "            //colums\n";
        $key = "";
        foreach ($array_colums as $name_colum => $attrubetes) {
            $length = isset($attrubetes["length"]) ? ", $attrubetes[length]" : '';
            $default = '';
            if (array_key_exists("unique", $attrubetes) && $attrubetes["unique"]) {
                $key = "->unique()";
            }
            if (isset($attrubetes["default"])) {
                if (in_array($attrubetes["default"], ['null', null])) {
                    $default = '->nullable()';
                } else {
                    $default = "->default(".$attrubetes["default"].")";
                }
            }
            $command .= '            $table->'.$array_types[$attrubetes['type']??'varchar'].'("'.$name_colum.'"'.$length.')'.$default.$key.";\n";
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
            $type = "";
            $mask = '';
            $option = '';
            if (array_key_exists('type', $attrubetes))
                switch ($attrubetes['type']) {
                    case 'decimal':
                        $mask = 'mascara="decimal"';
                        break;

                    case 'int':
                        $mask = 'mascara="inteiro"';
                        break;
                    case 'boolean':
                        $option = ":options=\"{1:'Sim', 0:'Não'}\"";
                        break;
                    case 'datetime':
                        $type = "type=\"datetime-local\"";
                        break;
                    case "date":
                        $type = "type=\"date\"";
                        break;
                    default:
                        $type = '';
                        $option = '';
                        $mask = '';
                        break;
                }
            $label = ucwords(str_replace('_', ' ', $name_colum));
            $commandInput .= "            <inputComponent label=\"$label\" name=\"$name_colum\" $mask $type $option></inputComponent>\n";
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

            if (array_key_exists('unique', $attrubetes) && $attrubetes['unique']) {
                $rules .= !array_key_exists('default', $attrubetes) ? "|unique:".$this->argument('name'): "unique:".$this->argument('name');
            }

            if (array_key_exists('type', $attrubetes))
                switch ($attrubetes['type']) {
                    case 'decimal':
                        $rules .= !array_key_exists('default', $attrubetes) || (array_key_exists('unique', $attrubetes) && $attrubetes['unique']) ? "|numeric" : "numeric";
                        break;
                    case 'int':
                        $rules .= !array_key_exists('default', $attrubetes) || (array_key_exists('unique', $attrubetes) && $attrubetes['unique']) ? "|numeric" : "numeric";
                        break;
                    case 'date':
                        $rules .= !array_key_exists('default', $attrubetes) || (array_key_exists('unique', $attrubetes) && $attrubetes['unique']) ? "|date" : "date";
                        break;
                    case 'datetime':
                        $rules .= !array_key_exists('default', $attrubetes) || (array_key_exists('unique', $attrubetes) && $attrubetes['unique']) ? "|date" : "date";
                        break;
                    case 'boolean':
                        $rules .= !array_key_exists('default', $attrubetes) || (array_key_exists('unique', $attrubetes) && $attrubetes['unique']) ? "|boolean" : "boolean";
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
            'controller'=>"Um novo arquivo controller para Form foi criado com sucesso",
            'model'=>"Um novo arquivo Model para Form foi criado com sucesso",
            'update'=>"Um novo arquivo Update para Form foi criado com sucesso",
            'store'=>"Um novo arquivo Store para Form foi criado com sucesso",
            'migration'=>"Um novo arquivo Migration para Form foi criado com sucesso",
            'seeder'=>"Um novo arquivo Seeder para Form foi criado com sucesso",
            'factory'=>"Um novo arquivo Factory para Form foi criado com sucesso",
            'view'=>"Um novo arquivo View para Form foi criado com sucesso",
            'vue'=>"Um novo arquivo Vue para Form foi criado com sucesso",
        ];

        foreach ($commands as $key => $value) {
            Artisan::call('form:'.$key, ['name'=>$this->argument('name')]);
            $this->info($value);
        }
    }
}
