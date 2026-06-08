<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Services\FineService;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function __construct(private FineService $fineService) {}

    public function index(Request $request)
    {
        $fines = $this->fineService->getFines($request->only(['status', 'member_id', 'search']));
        $stats = $this->fineService->getStats();
        return view('admin.fines.index', compact('fines', 'stats'));
    }

    public function show(Fine $fine)
    {
        $fine->load(['transaction.bookCopy.book', 'member.user', 'payments']);
        return view('admin.fines.show', compact('fine'));
    }

    public function recordPayment(Request $request, Fine $fine)
    {
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,online',
            'reference'      => 'nullable|string|max:100',
        ]);

        try {
            $this->fineService->recordPayment($fine, $validated['amount'], $validated['payment_method'], $validated['reference'] ?? null);
            return back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function waive(Request $request, Fine $fine)
    {
        try {
            $this->fineService->waiveFine($fine, $request->input('reason'));
            return back()->with('success', 'Fine waived successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
