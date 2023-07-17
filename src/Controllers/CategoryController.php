<?php

namespace Azuriom\Plugin\Changelog\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Changelog\Models\Category;
use Azuriom\Plugin\Changelog\Models\Update;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Category::enabled()->exists()) {
            return view('changelog::index');
        }

        return $this->showCategory();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Azuriom\Plugin\Changelog\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showCategory($category);
    }

    protected function showCategory(Category $category = null)
    {
        $categories = Category::enabled()->withCount('updates')->get();
        $updates = $category !== null
            ? $category->updates()->paginate()
            : Update::latest()->with('category')->paginate();

        return view('changelog::show', [
            'category' => $category,
            'updates' => $updates,
            'categories' => $categories,
            'totalUpdates' => Update::count(),
        ]);
    }
}
