<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AddTrustProxyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-trust-proxy {ip?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add trust proxy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = '/proxies/custom.json';

        if (! Storage::exists($path)) {
            Storage::put($path, json_encode([]));
        }

        $proxies = json_decode(Storage::get($path), true) ?? [];

        $ip = $this->argument('ip') ?? $this->ask('Put IP Address');

        $proxies[] = $ip;

        Storage::put($path, json_encode($proxies, JSON_PRETTY_PRINT));

        $this->info('Total prefixes: '.count($proxies));
    }
}
