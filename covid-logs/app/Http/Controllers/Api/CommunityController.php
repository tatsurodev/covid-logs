<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Community;
use App\Http\Requests\Communities\StoreRequest;
use App\Http\Resources\Communities\IndexResource;
use App\Http\Resources\Communities\StoreResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Community::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IndexResource::collection(auth()->user()->communities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $community = Community::create($validated);
        return (new StoreResource($community))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, Community $community)
    {
        $validated = $request->validated();
        $community->update($validated);
        return (new StoreResource($community))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
