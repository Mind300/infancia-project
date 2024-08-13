<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\SearchNurseryRequest;
use App\Models\Nurseries;

class GuestController extends Controller
{
    /**
     * Store a newly search about nursery.
     */
    public function store(SearchNurseryRequest $request)
    {
        $search = $request->validated();

        $results = Nurseries::where('province', 'like', "%$search[province]%")
            ->whereHas('user', function ($query) use ($search) {
                $query->where('country', 'like', "%$search[country]%")->where('city', 'like', "%$search[city]%");
            })->get()->setVisible(['id', 'name']);

        return $results->isNotEmpty() ? contentResponse($results, fetchAll('Nurseries')) : messageResponse('No Nursery Found', 404);
    }

    /**
     * Display the specified nursery.
     */
    public function show(string $id)
    {
        $nurseryFind = Nurseries::with('user')->find($id)->setHidden(['employees_number', 'classes_number', 'branches_number', 'kids_number']);
        $nursery = arrayUnest($nurseryFind,'user');
        return contentResponse($nursery, fetchOne($nursery['name']));
    }
}
