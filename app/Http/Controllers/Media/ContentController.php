<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentRequest;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ContentController extends Controller
{
    public function __construct(private Content $contentModel) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = $this->contentModel->paginate(10);

        return inertia('media/ContentIndex', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('media/ContentCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentRequest $request)
    {
        $data = $request->validated();

        if ($data['photo'] && $data['photo'] instanceof UploadedFile) {
            $data['cover'] = $data['photo']->store('media/contents', 'public');
        }

        $content = $this->contentModel->create($data);

        return redirect()->route('media.contents.edit', $content->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = $this->contentModel->findOrFail($id);

        return inertia('media/ContentEdit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
