<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AfrofiliateImporter;
use Illuminate\Support\Facades\Log;

class RunAfrofiliateNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:afrofiliate-network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To run afrofiliate network and create cashbacks';

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
        try {
            if(getImporterYMLSettings('Networks_Afrofiliate_Importer_Cashbacks' )){
                $importer = new AfrofiliateImporter();
                dispatch($importer);
                $this->info('Afrofiliate network\'s cashbacks created successfully.');
            }
        } catch (\Exception $e) {
            $this->error("\e[41;97m Something went wrong. \e[0m");
            return $this->error($e->getMessage());
        }
    }
}
