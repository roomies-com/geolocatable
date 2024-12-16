<?php

namespace Roomies\Geolocatable\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use PharData;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class DownloadMaxmindDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geolocatable:download {service=maxmind-database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the latest Maxmind database.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $config = config("geolocatable.services.{$this->argument('service')}");

        $temporaryDirectory = TemporaryDirectory::make();

        try {
            $this->info('Downloading Maxmind database...');

            $tarPath = $temporaryDirectory->path('maxmind.tar.gz');

            $response = Http::withBasicAuth($config['account_id'], $config['license_key'])
                ->sink($tarPath)
                ->get('https://download.maxmind.com/geoip/databases/GeoLite2-City/download?suffix=tar.gz');

            if (! $response->successful()) {
                $this->error('Failed to download Maxmind database');

                return Command::FAILURE;
            }

            $this->info('Extracting Maxmind database...');

            (new PharData($tarPath))->extractTo($temporaryDirectory->path());

            $databasePath = glob($temporaryDirectory->path().'/**/*.mmdb', GLOB_BRACE)[0] ?? null;

            $this->info('Moving Maxmind database...');

            if (! rename($databasePath, storage_path($config['path']))) {
                $this->error('Failed to move Maxmind database');

                return Command::FAILURE;
            }
        } finally {
            $temporaryDirectory->delete();
        }

        $this->info('Maxmind database ready for use');

        return Command::SUCCESS;
    }
}
