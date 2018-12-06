<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WebCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:webcrawler';

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
     * @return mixed
     */
    public function handle()
    {
        $context = stream_context_create(array(
            'http' => array(
                'header' => 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201',
            ),
        ));
        $dom = file_get_html('https://www.sunfrog.com/', false, $context);
        $a = $dom->find('.ieSearchFix  .dropdown-menu li a');
        $categories = [];
        foreach ($a as $k => $value) {
            $category = [];
            $category['href'] = $value->href;
            $category['name'] = $value->innertext;
        }
//        dd($categories[1]);
        file_put_contents(base_path('resources/lang/en.json'), stripslashes(json_encode($categories, JSON_PRETTY_PRINT)));
    }
}
