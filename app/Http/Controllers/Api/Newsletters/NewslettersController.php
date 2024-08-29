<?php

namespace App\Http\Controllers\Api\Newsletters;

use App\Http\Controllers\Controller;
use App\Http\Requests\Newsletters\NewslettersRequest;
use App\Http\Requests\Newsletters\NewslikesRequest;

use App\Models\Newsletters;
use App\Models\NewslettersLikes;
use Illuminate\Support\Facades\DB;

class NewslettersController extends Controller
{
    // Variables
    private $nursery_id;
    private $user_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->id;
        $this->user_id = auth()->user()->id;
    }

    /**
     * Data helper function.
     */
    public function data($newsletter)
    {
        $data = [
            'id' => $newsletter->id,
            'title' => $newsletter->title,
            'description' => $newsletter->description,
            'likes_counts' => $newsletter->likes_counts,
            'liked' => $newsletter->newslikes->where('user_id', $this->user_id)->where('newsletter_id', $newsletter->id)->first() ? 1 : 0,
            'media' => $newsletter->getFirstMedia('Newsletters'),
            'created_at' => $newsletter->created_at,
        ];
        return $data;
    }
    /**
     * Display a listing of the newsletters.
     */
    public function index()
    {
        $newsletters = NewsLetters::get();
        $newsletters = $newsletters->map(function ($newsletter) {
            return $this->data($newsletter);
        });
        return contentResponse($newsletters, 'The Newsletters Counts Successfully');
    }
    /**
     * Store a newly created newsletter in storage.
     */
    public function store(NewslettersRequest $request)
    {
        DB::beginTransaction();

        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        $requestValidated['title'] = auth()->user()->name;
        try {
            $newsletter = NewsLetters::create($requestValidated);
            $newsletter->addMediaFromRequest('media')->toMediaCollection('Newsletters');
            DB::commit();
            return contentResponse($newsletter, 'Newsletter Created Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Display the specified newsletter.
     */
    public function show(string $id)
    {
        $newsletter = NewsLetters::find($id);
        $data = $this->data($newsletter);
        return contentResponse($data, fetchOne($newsletter->title));
    }

    /**
     * Update the specified newsletter in storage.
     */
    public function update(NewsLetters $request, NewsLetters $newsletter)
    {
        DB::beginTransaction();
        $requestValidated = $request()->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        try {
            $newsletter->update($requestValidated);
            DB::commit();
            return messageResponse('Newsletter Updated Scuessfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified newsletter from storage.
     */
    public function destroy(NewsLetters $newsletter)
    {
        try {
            $newsletter->forceDelete();
            return messageResponse('Newletter Deleted Successfully');
        } catch (\Throwable $error) {
            return messageResponse($error);
        }
    }


    public function likeOrUnlike(NewslikesRequest $request)
    {
        DB::beginTransaction();

        $requestValidated = $request->validated();
        $requestValidated['user_id'] = $this->user_id;
        try {
            $newsletter = NewsLetters::find($requestValidated['newsletter_id']);
            $userLike = NewslettersLikes::where('user_id', $this->user_id)->where('newsletter_id', $newsletter->id)->first();

            if ($userLike) {
                $userLike->forceDelete();
                $newsletter->decrement('likes_counts');
            } else {
                NewslettersLikes::create($requestValidated);
                $newsletter->increment('likes_counts');
            }

            $data = $this->data($newsletter);

            DB::commit();
            return contentResponse($data, 'Like Incremenet Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }
}
