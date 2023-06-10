<?php

namespace Larasense\StaticSiteGeneration\Console\Commands;

use Illuminate\Console\Command;
use Larasense\StaticSiteGeneration\DTOs\Page;
use Larasense\StaticSiteGeneration\Facades\StaticSite;

class ListPagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'static:list';

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
        if(!StaticSite::enabled()){
            $this->warn("SSG is disabled");
        }

        collect(StaticSite::all())->each(function(Page $page){
            $this->info("Controller: {$page->controller}");
            $this->info("Method: {$page->method}");
            $this->info("Uri: {$page->uri}");
            $this->table(['Urls'], $this->toRows($page->urls));
        });
    }

    /**
     * @param string|array<int,string> $urls
     * @return array<int|array<int,string>>
     */
    protected function toRows(string|array $urls): array
    {
        return collect([$urls])
            ->flatten()
            ->map(fn(string $url) => [$url])
            ->toArray();
    }
}
