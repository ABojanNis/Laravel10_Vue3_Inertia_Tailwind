<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RealtorListingImageController extends Controller
{
    /**
     * @param Listing $listing
     * @return Response
     */
    public function create(Listing $listing): Response
    {
        return Inertia::render(
            'Realtor/ListingImage/Create',
            [
                'listing' => $listing->load(['images'])
            ]
        );
    }

    /**
     * @param Listing $listing
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Listing $listing, Request $request): RedirectResponse
    {
        if ($request->hasFile('images')) {
            $request->validate([
                'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000'
            ], [
                'images.*.mimes' => 'The file should be in on of the formats: jpg, png, jpeg, webp'
            ]);

            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $listing->images()->create([
                    'filename' => $path
                ]);
            }
        }

        return redirect()->back()->with('success', 'Images uploaded successfully!');
    }

    /**
     * @param $listing
     * @param ListingImage $image
     * @return RedirectResponse
     */
    public function destroy($listing, ListingImage $image)
    {
        Storage::disk('public')->delete($image->filename);
        $image->delete();

        return redirect()->back()->with('success', 'Image was deleted!');
    }
}
