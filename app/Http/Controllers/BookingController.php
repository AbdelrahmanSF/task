<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function store(Request $request, Service $service): RedirectResponse
    {
        abort_unless($request->user()->isCustomer() && $service->status === 'active', 403);
        $data = $request->validate([
            'booking_date' => ['required','date','after_or_equal:today'],
            'start_time' => ['required','date_format:H:i'],
            'address' => ['required','string','max:1000'],
            'notes' => ['nullable','string','max:2000'],
        ]);
        Booking::create(array_merge($data, [
            'customer_id' => $request->user()->id,
            'service_id' => $service->id,
            'provider_id' => $service->provider_id,
            'status' => BookingStatus::Pending,
            'total_amount' => $service->price,
        ]));
        return redirect()->route('customer.bookings')->with('success', 'Booking request sent to the provider.');
    }

    public function customer(): View
    {
        return view('customer.bookings', ['bookings' => auth()->user()->bookingsAsCustomer()->with('service','provider')->latest()->get()]);
    }

    public function provider(): View
    {
        return view('provider.bookings', ['bookings' => auth()->user()->bookingsAsProvider()->with('service','customer')->latest()->get()]);
    }

    public function status(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->provider_id === $request->user()->id, 403);
        $request->validate(['status' => ['required','in:accepted,rejected,completed']]);
        $allowed = match ($booking->status) {
            BookingStatus::Pending => [BookingStatus::Accepted->value, BookingStatus::Rejected->value],
            BookingStatus::Accepted => [BookingStatus::Completed->value],
            default => [],
        };
        abort_unless(in_array($request->status, $allowed, true), 422);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Booking status updated.');
    }
}
