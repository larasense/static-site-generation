<?php

namespace Larasense\StaticSiteGeneration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Larasense\StaticSiteGeneration\Jobs\ProcessStaticContent;
use Larasense\StaticSiteGeneration\Facades\Stalled;

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
        $urls = Stalled::urls();
        //

        $this->output->progressStart(count($urls));
        //TODO: delete all files before recreating htmls and jsons
        foreach ($urls as $url) {
            $content = Http::get($url)->body();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->info("Pages Generated");
    }
}
