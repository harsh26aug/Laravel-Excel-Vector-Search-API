<?php
namespace App\Repositories;

use App\Models\Category;
use App\Services\CohereService;

class CategoryRepository
{
    /**
     * Service Object holding
     */
    protected $cohereService;

    public function __construct(CohereService $cohereService)
    {
        $this->cohereService = $cohereService;
    }

    /**
     * Get prioritize array of categories based on given input
     */
    public function searchEmbedding(string $query)
    {
        $queryEmbedding = $this->cohereService->generateEmbedding($query);

        if (!$queryEmbedding) {
            return null;
        }

        $categories = Category::all();
        $similarities = [];

        foreach ($categories as $category) {
            $categoryEmbedding = json_decode($category->embedding, true);
            $matchInfo = round($this->cohereService->cosineSimilarity($queryEmbedding, $categoryEmbedding), 5);
            $similarities[$category->id] = $matchInfo;
            $category->match = $matchInfo;
        }

        // Sort top categories based on the original topCategoryIds order
        $categories = $categories->sortByDesc('match');

        // Return the shuffled Categories
        return $categories;
    }
}
