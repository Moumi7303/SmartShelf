<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function inventory(Request $request)
    {
        // To be implemented fully in Phase 8
        $format = $request->input('format', 'html');
        
        if ($format === 'pdf') {
            return back()->with('info', 'PDF Export coming soon.');
        } elseif ($format === 'excel') {
            return back()->with('info', 'Excel Export coming soon.');
        }

        return view('admin.reports.inventory');
    }

    public function circulation(Request $request)
    {
        // To be implemented fully in Phase 8
        return view('admin.reports.circulation');
    }

    public function fines(Request $request)
    {
        // To be implemented fully in Phase 8
        return view('admin.reports.fines');
    }
}
