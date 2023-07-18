<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filters = $request->only([
            'priceFrom',
            'priceTo',
            'beds',
            'baths',
            'areaFrom',
            'areaTo',
        ]);

        return Inertia::render(
            'Listing/Index',
            [
                'filters' => $filters,
                'listings' => Listing::latest()
                    ->filter($filters)
                    ->paginate(10)
                    ->withQueryString()
            ]
        );
    }

    /**
     * @param Listing $listing
     * @return Response
     */
    public function show(Listing $listing): Response
    {
        return Inertia::render(
            'Listing/Show',
            [
                'listing' => $listing->load('images')
            ]
        );
    }
}
