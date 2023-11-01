<?php

namespace Larasense\StaticSiteGeneration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Larasense\StaticSiteGeneration\Facades\StaticSite;

class GenerateStaticSiteCommand extends Command
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
    protected $description = 'generate Html & Json files for the defined routes.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if(!StaticSite::enabled()) {
            $this->error("SSG is disabled");
            return;
        }
        $urls = StaticSite::urls();
        $output = $this->output;

        $output->progressStart(count($urls));
        //TODO: delete all files before recreating htmls and jsons
        foreach ($urls as $url) {
            $response = Http::get($url);
            if($response->status() !== 200){
                abort(500,"Something Didn't work as expected. Go to $url to see more details about the problem");
            }
            if (StaticSite::withInertia()) {
                Http::withHeaders(['X-Inertia' => 'true'])->get($url);
            }
            $output->progressAdvance();
        }
        $output->progressFinish();
        $this->info("Pages Generated");
    }
}
