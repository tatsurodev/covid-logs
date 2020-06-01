<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Place;
use App\Http\Requests\Places\StoreRequest;
use App\Http\Resources\Places\IndexResource;
use App\Http\Resources\Places\StoreResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Place::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IndexResource::collection(auth()->user()->places);
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
        $place = Place::create($validated);
        return (new StoreResource($place))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        return new StoreResource($place);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, Place $place)
    {
        $validated = $request->validated();
        $place->update($validated);
        return (new StoreResource($place))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        $place->delete();
        // response()->setStatusCode(204)だけだと500が返ってくるので注意
        // return response()->noContent()->setStatusCode(Response::HTTP_NO_CONTENT);
        return response([], Response::HTTP_NO_CONTENT);
    }
}
