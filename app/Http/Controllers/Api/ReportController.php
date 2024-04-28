<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    /**
     * Display a listing of reports for a specific user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $reports = Report::where('user_id', $user->id)->get();

        return ReportResource::collection($reports);
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);
    
        if (!Restaurant::find($request->restaurant_id)) {
            throw ValidationException::withMessages([
                'restaurant_id' => ['No restaurant found with the provided ID.']
            ]);
        }
    
        $report = Report::create([
            'user_id' => null,
            'restaurant_id' => $request->restaurant_id,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'status' => 'Pending'
        ]);
    
        return response()->json([
            'message' => 'Report created successfully.',
            'report' => $report
        ], 201);
    }

    /**
     * Store a newly created report in storage for a specific user.
     */
    public function storeAuthenticated(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);
    
        if (!Restaurant::find($request->restaurant_id)) {
            throw ValidationException::withMessages([
                'restaurant_id' => ['No restaurant found with the provided ID.']
            ]);
        }
    
        $report = Report::create([
            'user_id' => $user->id,
            'restaurant_id' => $request->restaurant_id,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'status' => 'Pending'
        ]);
    
        return response()->json([
            'message' => 'Report created successfully.',
            'report' => $report
        ], 201);
    }

    /**
     * Update the specified report in storage.
     */
    public function update(Request $request, $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }

        $report->update([
            'notes' => $request->notes
        ]);

        return response()->json([
            'message' => 'Report updated successfully.',
            'report' => $report
        ]);
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully.'], 204);
    }
}
