<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use Illuminate\Console\Command;

class AppInstall extends Command
{
    protected $signature = 'app:install';
    protected $description = 'Install application';

    public function handle(): int
    {
        $this->migration();
        $this->line(PHP_EOL);
        $this->importMerchant();

        return Command::SUCCESS;
    }

    private function migration(): static
    {
        $this->info('Migration ...');
        $this->call('migrate:refresh');
        $this->info('Done!');

        return $this;
    }

    private function importMerchant(): static
    {
        $this->info('Import merchants ...');

        foreach (config('merchants') as $merchantConfig) {
            $merchant = Merchant::query()
                ->firstOrCreate(
                    attributes: ['external_id' => $merchantConfig['id']],
                    values: [
                        'api_key' => $merchantConfig['api_key'],
                        'daily_limit' => $merchantConfig['daily_limit'],
                    ]
                );

            $this->comment(sprintf('import merchant: %s', $merchant->id));
        }

        $this->info('Done!');

        return $this;
    }
}
