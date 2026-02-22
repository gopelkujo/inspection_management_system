<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InspectionService;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    protected InspectionService $inspectionService;

    public function __construct(InspectionService $inspectionService)
    {
        $this->inspectionService = $inspectionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Inspection::with('items')->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(10));
    }

    public function store(\App\Http\Requests\StoreInspectionRequest $request)
    {
        $validated = $request->validated();
        $userId = $request->user()?->id ?? 1;

        $inspection = $this->inspectionService->createInspection($validated, $userId);

        return response()->json($inspection->load('items'), 201);
    }

    public function show(string $id)
    {
        $inspection = \App\Models\Inspection::with('items')->findOrFail($id);
        return response()->json($inspection);
    }

    public function update(\App\Http\Requests\UpdateInspectionRequest $request, string $id)
    {
        $inspection = \App\Models\Inspection::findOrFail($id);
        
        try {
            $updatedInspection = $this->inspectionService->updateInspection($inspection, $request->validated());
            return response()->json($updatedInspection->load('items'));
        } catch (\Exception $e) {
            $status = $e->getCode() ?: 400;
            // Map common HTTP status codes or default to 400
            if (!in_array($status, [403, 422])) {
                 $status = 400; 
            }
            return response()->json(['message' => $e->getMessage()], $status);
        }
    }
}
