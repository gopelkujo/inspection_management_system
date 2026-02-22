<?php

namespace App\Services;

use App\Models\Inspection;
use Exception;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    /**
     * Store a new inspection with its items.
     */
    public function createInspection(array $data, int $userId): Inspection
    {
        return DB::transaction(function () use ($data, $userId) {
            $inspection = Inspection::create([
                'user_id' => $userId,
                'type' => $data['type'],
                'status' => 'OPEN', // Default starting status
                'metadata' => $data['metadata'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $inspection->items()->create($item);
            }

            return $inspection;
        });
    }

    /**
     * Update an inspection based on payload.
     * Determines whether it's a content update or status transition.
     */
    public function updateInspection(Inspection $inspection, array $data): Inspection
    {
        // Simple logic: If submitting status, update status.
        // If content provided, check if editable.
        if (isset($data['status'])) {
            $this->transitionStatus($inspection, $data['status']);
            return $inspection;
        }

        // Editing content
        if ($inspection->status !== 'OPEN') {
            throw new Exception('Cannot edit inspection in current status', 403);
        }

        // Start Transaction for full update
        DB::transaction(function () use ($inspection, $data) {
             $inspection->update([
                 'type' => $data['type'] ?? $inspection->type,
                 'metadata' => $data['metadata'] ?? $inspection->metadata
             ]);

             if (isset($data['items'])) {
                 $inspection->items()->delete();
                 foreach ($data['items'] as $item) {
                     $inspection->items()->create($item);
                 }
             }
        });

        return $inspection;
    }

    /**
     * Handles strict status transition rules.
     */
    protected function transitionStatus(Inspection $inspection, string $newStatus): void
    {
        $currentStatus = $inspection->status;

        // Prevent updating to the same status
        if ($currentStatus === $newStatus) {
            return; 
        }

        $validTransitions = [
            'OPEN' => ['FOR_REVIEW'],
            'FOR_REVIEW' => ['COMPLETED', 'OPEN'], // Optionally allow sending back to open
            'COMPLETED' => [] // Terminal state
        ];

        // Ensure current status has valid transitions
        if (!isset($validTransitions[$currentStatus])) {
             throw new Exception("Invalid current status: {$currentStatus}", 422);
        }

        // Ensure new status is a valid transition path
        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
             throw new Exception("Invalid transition from {$currentStatus} to {$newStatus}", 422);
        }

        $inspection->status = $newStatus;
        $inspection->save();
    }
}
