<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Offer;
use App\Notifications\OfferMade;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ListingOfferController extends Controller
{
    /**
     * @param Listing $listing
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Listing $listing, Request $request)
    {
        $this->authorize('view', $listing);

        $offer = $listing->offers()->save(
            Offer::make(
                $request->validate([
                    'amount' => 'required|integer|min:1|max:20000000'
                ])
            )->bidder()->associate($request->user())
        );

        $listing->owner->notify(
            new OfferMade($offer)
        );

        return redirect()->back()->with('success', 'Offer was made!');
    }
}
