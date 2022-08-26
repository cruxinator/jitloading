<?php

namespace Cruxinator\JitLoading\Commands;

use Illuminate\Console\Command;

class JitLoadingCommand extends Command
{
    public $signature = 'jitloading';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
