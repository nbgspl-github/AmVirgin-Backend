<?php

namespace App\Console\Commands;

use App\Classes\CellNavigator;
use App\Classes\ColumnNavigator;
use App\Traits\GenerateUrls;
use Illuminate\Console\Command;

class CodeTester extends Command
{
    use GenerateUrls;

    const TwoHours = 7200;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return void
     */
    public function handle()
    {
        $navigator = new ColumnNavigator();
        for ($i = 0; $i < 75; $i++) {
            echo $navigator->currentColumn() . "\n";
            $navigator->nextColumn();
        }
    }
}