<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreEventRequest;
use App\Http\Requests\Staff\UpdateEventRequest;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.event.index', [
            'events' => Event::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): \Illuminate\Http\RedirectResponse
    {
        Event::create($request->validated());

        return to_route('staff.events.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.event.edit', [
            'event' => $event->load('prizes'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): \Illuminate\Http\RedirectResponse
    {
        $event->update($request->validated());

        return to_route('staff.events.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): \Illuminate\Http\RedirectResponse
    {
        $event->delete();

        return to_route('staff.events.index');
    }
}
