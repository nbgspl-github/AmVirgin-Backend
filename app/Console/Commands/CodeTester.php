<?php

namespace App\Console\Commands;

use App\Classes\Singletons\RazorpayClient;
use App\Library\Utils\Extensions\Time;
use App\Models\News\Article;
use Illuminate\Console\Command;

class CodeTester extends Command
{
    use \App\Traits\NotifiableViaSms;

    const TwoHours = 7200;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:t';

    protected $mobile = "8375976617";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected \Razorpay\Api\Api $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
        $this->client = RazorpayClient::make();
    }

    public function handle ()
    {
        dd(\App\Models\News\Article::query()->whereNotNull('published_at')->get()->transform(function (Article $article)
        {
            return $article->published_at->format(Time::MYSQL_FORMAT) . "\n";
        }));
    }
}