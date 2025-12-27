<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateAwsProxiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:proxies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AWS Proxies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $resp = Http::get('https://ip-ranges.amazonaws.com/ip-ranges.json')->throw();
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $proxies = collect($resp->json('prefixes') ?? [])->map(fn ($p) => $p['ip_prefix']);

        Storage::put('proxies/aws.json', $proxies->toJson(JSON_PRETTY_PRINT));

        $this->info("Total prefixes: {$proxies->count()}");

        return Command::SUCCESS;
    }
}
