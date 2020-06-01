<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Companion;
use App\Http\Requests\Companions\StoreRequest;
use App\Http\Resources\Companions\IndexResource;
use App\Http\Resources\Companions\StoreResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Companion::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IndexResource::collection(auth()->user()->companions);
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
        $companion = Companion::create($validated);
        return (new StoreResource($companion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Companion $companion)
    {
        return new StoreResource($companion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, Companion $companion)
    {
        $validated = $request->validated();
        $companion->update($validated);
        return (new StoreResource($companion))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Companion $companion)
    {
        $companion->delete();
        // response()->setStatusCode(204)だけだと500が返ってくるので注意
        // return response()->noContent()->setStatusCode(Response::HTTP_NO_CONTENT);
        return response([], Response::HTTP_NO_CONTENT);
    }
}
