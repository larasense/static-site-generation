<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessStaticContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $content, protected string $path)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Storage::disk('html')->put(
            $this->path,
            $this->content,
            ['CacheControl' => 'public,max-age=60,no-transform']
        );
    }
}
