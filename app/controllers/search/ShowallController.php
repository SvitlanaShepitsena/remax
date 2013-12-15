<?php

//LOCATION: remax/public/search

class ShowallController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $houses = Listing::with('images', 'agent')->paginate(5);

        return View::make('search.showall');
    }
}