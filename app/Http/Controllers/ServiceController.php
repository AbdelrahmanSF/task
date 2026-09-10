<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::with(['provider', 'category'])->where('status', 'active');
        if ($request->filled('search')) {
            $term = $request->string('search');
            $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%"));
        }
        if ($request->filled('category')) $query->where('category_id', $request->integer('category'));
        if ($request->sort === 'price_asc') $query->orderBy('price');
        elseif ($request->sort === 'price_desc') $query->orderByDesc('price');
        elseif ($request->sort === 'rating') $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
        else $query->latest();
        return view('services.index', ['services' => $query->paginate(12)->withQueryString(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->status === 'active' && ! $service->trashed(), 404);
        $service->load(['provider', 'category', 'reviews.customer']);
        return view('services.show', compact('service'));
    }

    public function manage(): View
    {
        return view('provider.services.index', ['services' => auth()->user()->services()->with('category')->latest()->get()]);
    }

    public function create(): View
    {
        return view('provider.services.form', ['service' => new Service(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['provider_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        Service::create($data);
        return redirect()->route('provider.services')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        $this->authorizeService($service);
        return view('provider.services.form', ['service' => $service, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeService($service);
        $service->update($this->validated($request));
        return redirect()->route('provider.services')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorizeService($service);
        $service->delete();
        return back()->with('success', 'Service removed successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required','string','max:255'], 'description' => ['required','string'],
            'category_id' => ['required','exists:categories,id'], 'price' => ['required','numeric','min:0'],
            'duration_minutes' => ['required','integer','min:1'], 'status' => ['required','in:active,inactive'],
        ]);
    }

    private function authorizeService(Service $service): void
    {
        abort_unless($service->provider_id === auth()->id(), 403);
    }
}
