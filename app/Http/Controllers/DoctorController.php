<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     */
    public function index()
    {
        try {
            $doctors = Doctor::all();

            return response()->json([
                'success' => true,
                'data' => $doctors
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching doctors: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch doctors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created doctor in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Creating doctor with data:', $request->all());

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'image' => 'nullable|string|max:2048', // Changed from 'url' to 'string'
                'schedule' => 'nullable|array',
                'schedule.*.day' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'schedule.*.time_range' => 'string|max:255',
            ]);

            // Clean up the image field - remove if empty
            if (empty($validatedData['image'])) {
                $validatedData['image'] = null;
            }

            Log::info('Validated data:', $validatedData);

            $doctor = Doctor::create($validatedData);

            Log::info('Doctor created successfully:', $doctor->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Doctor created successfully',
                'data' => $doctor
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating doctor:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error creating doctor:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create doctor: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified doctor.
     */
    public function show(Doctor $doctor)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $doctor
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error showing doctor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified doctor in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            Log::info('Updating doctor with data:', $request->all());

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'image' => 'nullable|string|max:2048',
                'schedule' => 'nullable|array',
                'schedule.*.day' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'schedule.*.time_range' => 'string|max:255',
            ]);

            if (empty($validatedData['image'])) {
                $validatedData['image'] = null;
            }

            Log::info('Validated update data:', $validatedData);

            $doctor->update($validatedData);

            Log::info('Doctor updated successfully:', $doctor->fresh()->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Doctor updated successfully',
                'data' => $doctor->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating doctor:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating doctor:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update doctor: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified doctor from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            Log::info('Deleting doctor:', $doctor->toArray());

            $doctor->delete();

            Log::info('Doctor deleted successfully');

            return response()->json([
                'success' => true,
                'message' => 'Doctor deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
