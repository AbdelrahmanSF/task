<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View { return view('admin.categories', ['categories' => Category::orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required','string','max:255','unique:categories,name']]);
        $data['slug'] = str($data['name'])->slug();
        Category::create($data);
        return back()->with('success','Category created.');
    }
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate(['name' => ['required','string','max:255','unique:categories,name,'.$category->id]]);
        $category->update(['name'=>$data['name'],'slug'=>str($data['name'])->slug()]);
        return back()->with('success','Category updated.');
    }
    public function destroy(Category $category): RedirectResponse { $category->delete(); return back()->with('success','Category deleted.'); }
}
