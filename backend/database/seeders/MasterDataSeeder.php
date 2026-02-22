<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Inspection Types
            ['type' => 'inspection_type', 'code' => 'NEW_ARRIVAL', 'label' => 'New Arrival'],
            ['type' => 'inspection_type', 'code' => 'MAINTENANCE', 'label' => 'Maintenance'],
            ['type' => 'inspection_type', 'code' => 'ON_SPOT', 'label' => 'On Spot'],

            // Statuses
            ['type' => 'inspection_status', 'code' => 'OPEN', 'label' => 'Open'],
            ['type' => 'inspection_status', 'code' => 'FOR_REVIEW', 'label' => 'For Review'],
            ['type' => 'inspection_status', 'code' => 'COMPLETED', 'label' => 'Completed'],

            // Conditions
            ['type' => 'condition', 'code' => 'GOOD', 'label' => 'Good'],
            ['type' => 'condition', 'code' => 'DAMAGED', 'label' => 'Damaged'],
            ['type' => 'condition', 'code' => 'PENDING', 'label' => 'Pending'],

            // Owners
            ['type' => 'owner', 'code' => 'VENDOR_A', 'label' => 'Vendor A'],
            ['type' => 'owner', 'code' => 'VENDOR_B', 'label' => 'Vendor B'],
            ['type' => 'owner', 'code' => 'INTERNAL', 'label' => 'Internal'],

            // Allocations
            ['type' => 'allocation', 'code' => 'WH_1', 'label' => 'Warehouse 1'],
            ['type' => 'allocation', 'code' => 'WH_2', 'label' => 'Warehouse 2'],
            ['type' => 'allocation', 'code' => 'ZONE_A', 'label' => 'Zone A'],
        ];

        foreach ($data as $item) {
            \App\Models\MasterData::updateOrCreate(
                ['type' => $item['type'], 'code' => $item['code']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
