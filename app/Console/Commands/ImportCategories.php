<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use App\Models\Category;


class ImportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:categories {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an Excel file and process its data';

    /**
     * Batch size to process record in one batch
     */
    Protected $batchSize = 200;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = env("BASE_PATH") . $this->argument('file');

        // Check if the file exists
        if (!file_exists($file)) {
            $this->error("The file does not exist.");
            return;
        }

        try {
            // Use the reader for large files with streaming
            $reader = IOFactory::createReaderForFile($file);
            $reader->setReadDataOnly(true);

            // Open the file in a memory-efficient way using the read filter
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();

            // Set the start row and initialize the batch
            $rowNumber = 2;
            $processedData = [];

            // Iterate over the rows
            foreach ($sheet->getRowIterator($rowNumber) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Ensure all cells are iterated
                $rowData = [];

                // Collect the row data (column mapping)
                foreach ($cellIterator as $cell) {
                    $column = $cell->getColumn();
                    $value = $cell->getFormattedValue();

                    // Assigning values based on column
                    if ($column == 'A') {
                        $rowData['name'] = $value;
                    } elseif ($column == 'B') {
                        $rowData['sub_category'] = $value;
                    } elseif ($column == 'C') {
                        $rowData['service'] = $value;
                    } elseif ($column == 'D') {
                        $rowData['keywords'] = $value;
                    }
                }

                // Add the row to the processed data batch
                if (!empty($rowData)) {
                    $processedData[] = $rowData;
                }

                // If batch size is reached, perform bulk insert and clear memory
                if (count($processedData) >= $this->batchSize) {
                    Category::insert($processedData);
                    $this->info("Inserted a batch of " . count($processedData) . " records.");
                    $processedData = []; // Clear the batch
                }

                // Optionally, we can clear the processed row to free memory
                unset($rowData);
            }

            // Insert remaining data after loop finishes
            if (!empty($processedData)) {
                Category::insert($processedData);
                $this->info("Inserted final batch of " . count($processedData) . " records.");
            }

            $this->info('Data successfully imported to the database.');
        } catch (Exception $e) {
            $this->error("Error reading the file: " . $e->getMessage());
        }
    }
}
