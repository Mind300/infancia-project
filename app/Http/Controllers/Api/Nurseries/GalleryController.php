<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nurseries\AlbumRequest;
use App\Http\Requests\Nurseries\AlbumUpdateRequest;
use App\Http\Requests\Nursery\GalleryRequest;
use App\Models\Album;

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
    public function index(string $nursery_id)
    {
        $albums = Album::where('nursery_id', $nursery_id)->get();
        return contentResponse($albums, fetchAll('Nursery Album'));
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
    public function edit(string $album_id)
    {
        $album = Album::findOrFail($album_id);
        $mediaItems = $album->getMedia($album->title);
        $mediaArray = $mediaItems->values()->toArray();
        return contentResponse($mediaArray, fetchAll('Albums Name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlbumUpdateRequest $request, string $album_id)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        $album = Album::findOrFail($album_id)->update($requestValidated);
        return messageResponse('Album Updating Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $album = Album::findOrFail($id);
        $album->clearMediaCollection($album->title);
        $album->forceDelete();
        return messageResponse('Album Deleted Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function deletePhoto(string $album_id, string $media_id)
    {
        $album = Album::findOrFail($album_id);
        $mediaItem = $album->getMedia($album->title)->where('id', $media_id)->first();
        if ($mediaItem) {
            $mediaItem->delete(); // Delete the specific media item
            return messageResponse('Photo Deleted Successfully');
        } else {
            return messageResponse('Photo Not Found', 404);
        }
    }
}
