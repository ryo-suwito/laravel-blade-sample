<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateCloudflareProxiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudflare:proxies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Cloudflare Proxies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $proxies = [
            ...$this->fetchIpv4(),
            ...$this->fetchIpv6(),
        ];

        Storage::put('proxies/cloudflare.json', json_encode($proxies, JSON_PRETTY_PRINT));

        $this->info('Total prefixes: '.count($proxies));

        return Command::SUCCESS;
    }

    private function fetchIpv4(): array
    {
        try {
            $resp = Http::get('https://www.cloudflare.com/ips-v4')->throw();
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return explode("\n", $resp);
    }

    private function fetchIpv6()
    {
        try {
            $resp = Http::get('https://www.cloudflare.com/ips-v6')->throw();
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return explode("\n", $resp);
    }
}
