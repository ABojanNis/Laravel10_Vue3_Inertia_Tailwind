<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RealtorListingController extends Controller
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
        $filters = [
            'deleted' => $request->boolean('deleted'),
            ... $request->only(['by', 'order'])
        ];

        return Inertia::render(
            'Realtor/Index',
            [
                'filters' => $filters,
                'listings' => Auth::user()->listings()
                    ->filter($filters)
                    ->withCount('images')
                    ->withCount('offers')
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
            'Realtor/Show',
            [
                'listing' => $listing->load('offers', 'offers.bidder')
            ]
        );
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render(
            'Realtor/Create'
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->user()->listings()->create(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000',
            ])
        );

        return redirect()->route('realtor.listing.index')->with('success', 'Listing was created!');
    }

    /**
     * @param Listing $listing
     * @return Response
     */
    public function edit(Listing $listing): Response
    {
        return Inertia::render(
            'Realtor/Edit',
            [
                'listing' => $listing
            ]
        );
    }

    /**
     * @param Request $request
     * @param Listing $listing
     * @return RedirectResponse
     */
    public function update(Request $request, Listing $listing): RedirectResponse
    {
        $listing->update(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000',
            ])
        );

        return redirect()->route('realtor.listing.index')->with('success', 'Listing was changed!');
    }

    /**
     * @param Listing $listing
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function destroy(Listing $listing): RedirectResponse
    {
        $listing->deleteOrFail();

        return redirect()->back()
            ->with('success', 'Listing was deleted!');
    }

    /**
     * @param Listing $listing
     * @return RedirectResponse
     */
    public function restore(Listing $listing): RedirectResponse
    {
        $listing->restore();

        return redirect()->back()
            ->with('success', 'Listing was restored!');
    }
}
