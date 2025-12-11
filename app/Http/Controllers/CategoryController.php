<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Category repository object
     */
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Search page load with related category list
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return view('category_search', ['query' => '', 'results' => []]);
        }

        $results = $this->categoryRepository->searchEmbedding($query);

        return view('category_search', [
            'query' => $query,
            'results' => $results
        ]);
    }
}
