<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\NewsRequest;
use App\Http\Resources\Content\NewsResource;
use App\Models\Content\News;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', '');
        $page = request('page', 1);
        $per_page = request('per_page', 5);

        $news = News::query()
            ->with('author')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate($per_page);

        return Inertia::render('content/news/Index', [
            'news' => NewsResource::collection($news),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('content/news/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        $request->user()->news()->create([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
        ]);

        return redirect()->route('news.index')->with('success', 'News article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewsRequest $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
