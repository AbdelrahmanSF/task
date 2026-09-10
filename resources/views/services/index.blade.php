<x-app-layout>
<div class="max-w-7xl mx-auto p-6">
<h1 class="text-3xl font-bold mb-6">Find a Service</h1>
<form class="grid md:grid-cols-4 gap-3 mb-8" method="GET">
<input name="search" value="{{ request('search') }}" placeholder="Search services..." class="rounded border-gray-300">
<select name="category" class="rounded border-gray-300"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category')==$category->id)>{{ $category->name }}</option>@endforeach</select>
<select name="sort" class="rounded border-gray-300"><option value="">Newest</option><option value="price_asc">Price: low to high</option><option value="price_desc">Price: high to low</option><option value="rating">Top rated</option></select>
<button class="rounded bg-indigo-600 px-4 py-2 text-white">Search</button>
</form>
<div class="grid md:grid-cols-3 gap-6">@forelse($services as $service)<a href="{{ route('services.show',$service) }}" class="block rounded-xl border bg-white p-5 shadow-sm hover:shadow-md"><div class="text-sm text-gray-500">{{ $service->category->name }}</div><h2 class="text-xl font-semibold mt-1">{{ $service->title }}</h2><p class="mt-2 text-gray-600">{{ Str::limit($service->description,100) }}</p><div class="mt-4 font-bold">{{ number_format($service->price,2) }}</div><div class="text-sm">{{ $service->duration_minutes }} min · {{ $service->provider->name }}</div></a>@empty<p>No services found.</p>@endforelse</div>
<div class="mt-6">{{ $services->links() }}</div>
</div></x-app-layout>
