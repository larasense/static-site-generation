<?php

namespace Larasense\StaticSiteGeneration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Facades\Larasense\StaticSiteGeneration\Services\File;
use Larasense\StaticSiteGeneration\Facades\StaticSite;


class DisableCacheCommand extends Command
{
    use ConfirmableTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'static:disable-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable SSG cache';

    /**
     * Execute the console command.
     */
    public function handle():int
    {
        if (!StaticSite::cached()){
            $this->info("SSG Cache is Already disabled.");
            return 0;
        }
        if (! $this->setEnvironmentFile()) {
            return 1;
        }

        $this->laravel['config']['staticsitegen.cached'] = 'redis';

        $this->components->info('Cache disabled successfully.');

        return 0;
    }

    /**
     * Set the application key in the environment file.
     *
     * @return bool
     */
    protected function setEnvironmentFile(): bool
    {
        $currentKey = $this->laravel['config']['staticsitegen.cached']? 'true':'false';

        if (strlen($currentKey) !== 'true' && (! $this->confirmToProceed())) {
            return false;
        }

        if (! $this->writeNewEnvironmentFile()) {
            return false;
        }

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @return bool
     */
    protected function writeNewEnvironmentFile(): bool
    {
        $input = File::get($this->laravel->environmentFilePath());
        if (!$input) {
            $this->error('Unable disable SSG Cache Driver. No .env file.');
            return false;
        }

        $replaced = preg_replace(
            $this->keyReplacementPattern(),
            'SSG_CACHE_ENABLED=false',
            $input
        );

        if ($replaced === $input || $replaced === null) {
            $replaced .= "\n\nSSG_CACHE_ENABLED=false\n";
        }

        File::set($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern(): string
    {
        $currentKey = $this->laravel['config']['staticsitegen.cached']? 'true':'false';
        $escaped = preg_quote('='.$currentKey, '/');

        return "/^SSG_CACHE_ENABLED{$escaped}/m";
    }
}

