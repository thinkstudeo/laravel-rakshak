<?php

namespace Thinkstudeo\Rakshak\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{

    /**
     * Signature to be used for calling the command from the cli, along with arguments and options.
     *
     * @var string
     */
    protected $signature = 'rakshak:publish {--force : Overwrite any existing files}';

    /**
     * Describe what the command does.
     *
     * @var string
     */
    protected $description = 'Publish all Rakshak views and config.';

    /**
     * Handle the execution logic for the command.
     * Publish the config and views of Rakshak.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->call('vendor:publish', [
            '--tag'   => 'config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag'   => 'views',
            '--force' => $this->option('force'),
        ]);
    }
}