<?php

namespace App\Console\Commands;

use App\Models\Brand;
use Illuminate\Console\Command;

class InsertRequestIdToBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brands:requestId';

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
        Brand::all()->each(function (Brand $brand) {
            $brand->update([
                'requestId' => random_str(25)
            ]);
        });
    }
}
