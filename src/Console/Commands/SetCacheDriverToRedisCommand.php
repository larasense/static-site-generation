<?php

namespace Larasense\StaticSiteGeneration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Facades\Larasense\StaticSiteGeneration\Services\File;
use Larasense\StaticSiteGeneration\Facades\StaticSite;

class SetCacheDriverToRedisCommand extends Command
{
    use ConfirmableTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'static:set-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!StaticSite::enabled()) {
            $this->error("SSG is disabled");
            return 1;
        }
        if (! $this->setEnvironmentFile()) {
            return 1;
        }

        $this->laravel['config']['cache.default'] = 'redis';

        $this->components->info('Cache set successfully.');

        return 0;
    }

    /**
     * Set the application key in the environment file.
     *
     * @return bool
     */
    protected function setEnvironmentFile(): bool
    {
        $currentKey = $this->laravel['config']['cache.default'];

        if (strlen($currentKey) !== 'redis' && (! $this->confirmToProceed())) {
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
            $this->error('Unable to set the Cache Driver to redis. No .env file.');
            return false;
        }

        $replaced = preg_replace(
            $this->keyReplacementPattern(),
            'CACHE_DRIVER=redis',
            $input
        );

        if ($replaced === $input || $replaced === null) {
            $this->error('Unable to set the Cache Driver to redis. No CACHE_DRIVER variable was found in the .env file.');

            return false;
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
        $escaped = preg_quote('='.$this->laravel['config']['cache.default'], '/');

        return "/^CACHE_DRIVER{$escaped}/m";
    }
}
