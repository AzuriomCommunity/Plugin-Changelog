<?php

namespace Azuriom\Plugin\Changelog\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Changelog\Models\Category;
use Azuriom\Plugin\Changelog\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('changelog::admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('changelog::admin.categories.edit', ['category' => $category]);
    }

    /**
     * Update the order of the resources.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateOrder(Request $request)
    {
        $this->validate($request, [
            'categories' => ['required', 'array'],
        ]);

        $categories = $request->input('categories');

        $categoryPosition = 1;

        foreach ($categories as $category) {
            $id = $category['id'];

            Category::whereKey($id)->update([
                'position' => $categoryPosition++,
            ]);
        }

        return response()->json([
            'message' => trans('changelog::admin.categories.updated'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->updates()->delete();
        $category->delete();

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('messages.status.success'));
    }
}
