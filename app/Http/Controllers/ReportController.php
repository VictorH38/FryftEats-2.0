<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Restaurant;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        $restaurants = Restaurant::orderBy('name', 'asc')->get();
        $selectedRestaurant = null;

        if ($request->has('restaurant_id')) {
            $selectedRestaurant = $restaurants->where('id', $request->restaurant_id)->first();
        }

        if (Auth::check()) {
            $userId = Auth::id();
            $reports = Report::where('user_id', $userId)
                ->with('restaurant')
                ->orderBy('created_at', 'desc')
                ->get();
    
            return view('reports.create', [
                'restaurants' => $restaurants,
                'reports' => $reports,
                'selectedRestaurant' => $selectedRestaurant
            ]);
        }
    
        return view('reports.create', [
            'restaurants' => $restaurants,
            'selectedRestaurant' => $selectedRestaurant
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $request->validate([
                'restaurant_id' => 'required|exists:restaurants,id',
                'reason' => 'required|string',
                'notes' => 'nullable|string'
            ]);

            Report::create([
                'user_id' => $userId,
                'restaurant_id' => $request->input('restaurant_id'),
                'reason' => $request->input('reason'),
                'notes' => $request->input('notes'),
                'status' => 'Pending'
            ]);

            return redirect()->back()->with('success', 'Report submitted successfully');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $report = Report::where('id', $id)->where('user_id', Auth::id())->first();

        if ($report) {
            $report->update([
                'notes' => $request->notes
            ]);
    
            return response()->json(['success' => true, 'message' => 'Report updated successfully']);
        }
    
        return response()->json(['success' => false, 'message' => 'There was an error updating your report'], 422);
    }

    public function destroy($id)
    {
        $report = Report::where('id', $id)->where('user_id', Auth::id())->first();
        if ($report) {
            $report->delete();
            return response()->json(['success' => true, 'message' => 'Report deleted successfully']);
        }
    
        return response()->json(['success' => false, 'message' => 'Your report could not be deleted'], 422);
    }
}
