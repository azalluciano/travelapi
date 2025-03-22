<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ExportDestinationsCommand extends Command
{
    protected $signature = 'destinations:export {filename=destinations.csv}';
    protected $description = 'Export all destinations to CSV file via API';

    public function handle()
    {
        $this->info('Fetching destinations from API...');

        try {
            // Get destinations from our own API
            $response = Http::get(config('app.url') . '/api/destinations');

            if ($response->failed()) {
                $this->error('Failed to fetch destinations from API. HTTP Status: ' . $response->status());
                return 1;
            }

            $destinations = $response->json();

            if (empty($destinations)) {
                $this->warn('No destinations found to export');
                return 0;
            }

            $filename = $this->argument('filename');
            $file = fopen($filename, 'w');

            if ($file === false) {
                $this->error('Failed to open file for writing');
                return 1;
            }

            // Write header row
            fputcsv($file, ['name', 'description', 'price', 'duration', 'image']);

            // Write data rows with error handling
            foreach ($destinations as $destination) {
                $row = [
                    $destination['name'] ?? 'N/A',
                    $destination['description'] ?? 'N/A',
                    $destination['price'] ?? '0',
                    $destination['duration'] ?? 'N/A',
                    $destination['image'] ?? 'N/A'
                ];

                if (!fputcsv($file, $row)) {
                    $this->error('Failed to write row to CSV file');
                    fclose($file);
                    return 1;
                }
            }

            fclose($file);
            $this->info(count($destinations) . ' destinations exported to ' . $filename);
            return 0;
        } catch (\Exception $e) {
            $this->error('Error during export: ' . $e->getMessage());
            return 1;
        }
    }
}
