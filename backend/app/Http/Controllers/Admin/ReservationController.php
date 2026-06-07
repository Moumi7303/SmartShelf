<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(private ReservationService $reservationService) {}

    public function index(Request $request)
    {
        $reservations = $this->reservationService->getReservations(
            $request->only(['status', 'member_id', 'search'])
        );

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['member.user', 'book.copies.branch']);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id'   => 'required|exists:books,id',
        ]);

        try {
            $this->reservationService->createReservation($validated['member_id'], $validated['book_id']);
            return redirect()->route('admin.reservations.index')->with('success', 'Reservation created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function approve(Reservation $reservation)
    {
        try {
            $this->reservationService->approveReservation($reservation);
            return back()->with('success', 'Reservation approved.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Reservation $reservation)
    {
        $this->reservationService->cancelReservation($reservation);
        return back()->with('success', 'Reservation cancelled.');
    }
}
