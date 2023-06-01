<?php

namespace Larasense\StaticSiteGeneration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Larasense\StaticSiteGeneration\Facades\StaticSite;

class GenerateStaticSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'static:generate-site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle():void
    {
        $urls = StaticSite::urls();
        $output = $this->output;

        $output->progressStart(count($urls));
        //TODO: delete all files before recreating htmls and jsons
        foreach ($urls as $url) {
            $content = Http::get($url)->body();
            $output->progressAdvance();
        }
        $output->progressFinish();
        $this->info("Pages Generated");
    }
}
