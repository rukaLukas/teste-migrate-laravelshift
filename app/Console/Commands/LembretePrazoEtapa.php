<?php

namespace App\Console\Commands;

use App\Mail\Accession\ErrataGestor;
use App\Mail\Record\VencimentoPrazoEncaminhamento;
use App\Models\Accession;
use App\Models\AlertStep;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LembretePrazoEtapa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lembrete:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'envia e-mail 7 dias antes do vencimento prazo encaminhamento';

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
        // TODO: envia e-mail 7 dias antes do vencimento prazo encaminhamento, se o caso não estiver concluído

        $this->info("running lembrete:run");

        $records = Record::with(['lastAlert'])->get();

        foreach ($records as $record) {
            if($record->lastStepstatus){
                if($record->lastStepstatus->statusAlert->id ===  StatusAlert::ENCAMINHAMENTO) {
                    if ($record->lastStepstatus->prazoAnalise['label'] === "Em atraso") {
                        if($record->lastAlert->mother_email) {
                            Mail::to($record->lastAlert->mother_email)->send(
                                new VencimentoPrazoEncaminhamento($record)
                            );
                        }
                        if($record->lastAlert->father_email) {
                            Mail::to($record->lastAlert->father_email)->send(
                                new VencimentoPrazoEncaminhamento($record)
                            );
                        }
                    }
                }
            }
        }
    }
}
