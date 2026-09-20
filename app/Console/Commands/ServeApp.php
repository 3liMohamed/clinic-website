<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class ServeApp extends Command
{
    protected $signature = 'serve:app';

    protected $description = 'Starts Octane with file watching enabled';

    public function handle(): int
    {
        return $this->call('octane:start', [
            '--watch' => true,
        ]);
    }
}
