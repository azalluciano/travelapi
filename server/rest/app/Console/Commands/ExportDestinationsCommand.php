<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ExportDestinationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destinations:export {filename=destinations.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all destinations to CSV file via API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching destinations from API...');

        // Get destinations from our own API
        $response = Http::get(config('app.url') . '/api/destinations');

        if ($response->failed()) {
            $this->error('Failed to fetch destinations from API');
            return 1;
        }

        $destinations = $response->json('data');

        if (empty($destinations)) {
            $this->warn('No destinations found to export');
            return 0;
        }

        $filename = $this->argument('filename');
        
        // Open file for writing
        $file = fopen($filename, 'w');
        
        // Write header row
        fputcsv($file, ['name', 'description', 'price', 'duration', 'image']);
        
        // Write data rows
        foreach ($destinations as $destination) {
            fputcsv($file, [
                $destination['name'],
                $destination['description'],
                $destination['price'],
                $destination['duration'],
                $destination['image']
            ]);
        }
        
        fclose($file);
        
        $this->info(count($destinations) . ' destinations exported to ' . $filename);
        
        return 0;
    }
}