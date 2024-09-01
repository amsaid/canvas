<?php

namespace Canvas\Commands;

use Illuminate\Console\Command;

class CanvasCommand extends Command
{
    public $signature = 'canvas';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
