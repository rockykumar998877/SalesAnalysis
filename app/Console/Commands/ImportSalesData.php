<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Sale;
class ImportSalesData extends Command
{
    protected $signature = 'sales:import {file}';
    protected $description = 'Import sales data from a CSV file';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error("File not found!");
            return;
        }

        $data = array_map('str_getcsv', file($file));
        array_shift($data); // Remove header row

        foreach ($data as $row) {
            Sale::create([
                'product' => $row[0],
                'quantity' => (int) $row[1],
                'price' => (float) $row[2],
                'date' => $row[3]
            ]);
        }

        $this->info("Sales data imported successfully!");
    }
}
