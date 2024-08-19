<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nurseries\AlbumRequest;
use App\Http\Requests\Nursery\GalleryRequest;
use App\Models\Album;
use App\Models\Nurseries;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    // Variables
    private $nursery_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = Album::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($albums, fetchAll('Nursery Album'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlbumRequest $request)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        
        $album = Album::create($requestValidated);
        return messageResponse('Album Adding Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function addPhotos(GalleryRequest $request)
    {
        $album = Album::findOrFail($request->validated('album_id'));
        $album->addMediaFromRequest('media')->toMediaCollection($album->title);
        return messageResponse('Photos Added in ' . $album->title . ' Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $album_id)
    {
        $album = Album::findOrFail($album_id);
        $mediaItems = $album->getMedia($album->title);
        $mediaArray = $mediaItems->values()->toArray();
        return contentResponse($mediaArray, fetchAll('Albums Name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

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
