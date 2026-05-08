<?php

namespace App\Controllers;

class MapController extends BaseController
{
    public function __construct()
    {
        helper('asset_helper');
    }

    public function index()
    {
        try {
            $mapModel = new \App\Models\Map();
            $barangays = $mapModel->findAll();

            // Log the retrieved data for debugging
            error_log("Retrieved barangays: " . json_encode($barangays));

            $locations = [];
            foreach ($barangays as $barangay) {
                // Only include barangays with status "Active"
                if (isset($barangay['status']) && $barangay['status'] === 'ACTIVE') {
                    $locations[] = [
                        'id' => $barangay['ID'],
                        'name' => $barangay['brgy_name'],
                        'details' => $barangay['details'],
                        'top' => $barangay['top_loc'],
                        'left' => $barangay['left_loc'],
                        'status' => $barangay['status'],
                    ];
                }
            }

            return view('map', ['locations' => $locations]);
        } catch (\Exception $e) {
            // Log the error
            error_log("Error in MapController::index: " . $e->getMessage());

            // Return an error view or message
            return view('errors/html/error_general', ['message' => 'An error occurred while loading the map. Please try again later.']);
        }
    }
}