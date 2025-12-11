<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Services\CohereService;

class GenerateCategoryEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:category-embeddings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Category Embedding';

    /**
     * Service Object holding
     */
    protected $cohereService;

    public function __construct(CohereService $cohereService)
    {
        parent::__construct();
        $this->cohereService = $cohereService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Process categories in chunks to avoid memory overload
        Category::whereNull('embedding')->chunk(100, function ($categories) {
            foreach ($categories as $category) {
                // Generate embedding for each category
                $embedding = $this->cohereService->generateEmbedding(
                    $category->name . ' - ' . 
                    $category->sub_category . ' - ' . 
                    $category->service
                );
                // Save the embedding to the category
                $category->embedding = json_encode($embedding);
                $category->save();
            }

            // Release memory after each chunk
            unset($categories);
        });

        $this->info('Category embeddings generated successfully.');
    }
}
