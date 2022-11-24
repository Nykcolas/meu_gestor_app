<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeFormHelp extends Command
{
    protected $signature = 'form:help';
    protected $description = 'Show de forms commands';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $headers = ['Type', 'Length', 'Default', 'Options', 'unique'];
        $rows = [
            [
                "type"=>"varchar -> padrão\nboolean\ndecimal\nint\ndate\ndatetime", 
                "length"=>"255 -> padrão varchar\n8,2 -> padrão decimal\n11 -> padrão int\n1 -> Padrão boolean", 
                "default"=>"Required -> Padrão\nnull\nCustom text", 
                "options"=>"array com opções de miltipla escolha", 
                "unique"=>"true\nfalse"
            ],
        ];
        
        $this->warn("controller");
        $this->info("Cria um novo arquivo controller para Form");
        $this->warn("model");
        $this->info("Cria um novo arquivo Model para Form");
        $this->warn("update");
        $this->info("Cria um novo arquivo Update para Form");
        $this->warn("store");
        $this->info("Cria um novo arquivo Store para Form");
        $this->warn("migration");
        $this->info("Cria um novo arquivo Migration para Form");
        $this->warn("seeder");
        $this->info("Cria um novo arquivo Seeder para Form");
        $this->warn("factory");
        $this->info("Cria um novo arquivo Factory para Form");
        $this->warn("view");
        $this->info("Cria um novo arquivo View para Form");
        $this->warn("vue");
        $this->info("Cria um novo arquivo Vue para Form");
        $this->warn("All");
        $this->info("Cria Form Completo, e caso seja solicitado, é possivel criar também as colunas da tabela do Form, Passando um Array;");
        $this->info("Os atributos possiveis são");
        $this->table($headers, $rows);
        $this->error(" Não recomendado em caso de FK ");

    }
}
