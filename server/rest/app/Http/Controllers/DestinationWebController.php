<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;


class DestinationWebController extends Controller
{
    /**
     * Display a listing of the destinations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $destinations = Destination::all();
        return view('destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new destination.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('destinations.create');
    }

    /**
     * Store a newly created destination in storage.
     *
     * @param  StoreDestinationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDestinationRequest $request)
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filePath = $image->store('images/destinations', 'public');
            $validatedData['image'] = url(Storage::url($filePath));
        } else {
            $validatedData['image'] = url('/default-image.jpg');
        }

        Destination::create($validatedData);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully');
    }

    /**
     * Display the specified destination.
     *
     * @param  Destination  $destination
     * @return \Illuminate\View\View
     */
    public function show(Destination $destination)
    {
        return view('destinations.show', compact('destination'));
    }

    /**
     * Show the form for editing the specified destination.
     *
     * @param  Destination  $destination
     * @return \Illuminate\View\View
     */
    public function edit(Destination $destination)
    {
        return view('destinations.edit', compact('destination'));
    }

    /**
     * Update the specified destination in storage.
     *
     * @param  UpdateDestinationRequest  $request
     * @param  Destination  $destination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it's not the default
            if ($destination->image && !str_contains($destination->image, 'default-image.jpg')) {
                $oldImagePath = str_replace(url('/storage/'), '', $destination->image);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            $image = $request->file('image');
            $filePath = $image->store('images/destinations', 'public');
            $validatedData['image'] = url(Storage::url($filePath));
        }

        $destination->update($validatedData);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully');
    }

    /**
     * Remove the specified destination from storage.
     *
     * @param  Destination  $destination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Destination $destination)
    {
        // Delete image if it's not the default
        if ($destination->image && !str_contains($destination->image, 'default-image.jpg')) {
            $oldImagePath = str_replace(url('/storage/'), '', $destination->image);
            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination deleted successfully');
    }

    /**
     * Display a listing of the destinations for the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $destinations = Destination::all();
        return view('home', compact('destinations'));
    }

    /**
     * Display the specified destination for public view.
     *
     * @param  Destination  $destination
     * @return \Illuminate\View\View
     */
    public function details(Destination $destination)
    {
        return view('destinations.details', compact('destination'));
    }
}
