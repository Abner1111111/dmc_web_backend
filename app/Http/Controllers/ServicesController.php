<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        try {
            $services = Service::all();

            return response()->json([
                'success' => true,
                'data' => $services
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching services: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Creating service with data:', $request->all());

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'image' => 'nullable|string|max:2048',
                'gallery' => 'nullable|array',
                'gallery.*' => 'string|max:2048', // Each gallery item should be a string (URL)
            ]);

            // Clean up the image field - remove if empty
            if (empty($validatedData['image'])) {
                $validatedData['image'] = null;
            }

            // Handle gallery - ensure it's an array
            if (!isset($validatedData['gallery']) || empty($validatedData['gallery'])) {
                $validatedData['gallery'] = [];
            }

            Log::info('Validated service data:', $validatedData);

            $service = Service::create($validatedData);

            Log::info('Service created successfully:', $service->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Service created successfully',
                'data' => $service
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating service:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error creating service:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create service: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $service
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error showing service: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        try {
            Log::info('Updating service with data:', $request->all());

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'image' => 'nullable|string|max:2048',
                'gallery' => 'nullable|array',
                'gallery.*' => 'string|max:2048', // Each gallery item should be a string (URL)
            ]);

            // Clean up the image field - remove if empty
            if (empty($validatedData['image'])) {
                $validatedData['image'] = null;
            }

            // Handle gallery - ensure it's an array
            if (!isset($validatedData['gallery']) || empty($validatedData['gallery'])) {
                $validatedData['gallery'] = [];
            }

            Log::info('Validated service update data:', $validatedData);

            $service->update($validatedData);

            Log::info('Service updated successfully:', $service->fresh()->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully',
                'data' => $service->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating service:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating service:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update service: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        try {
            Log::info('Deleting service:', $service->toArray());

            $service->delete();

            Log::info('Service deleted successfully');

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting service: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get services by category.
     */
    public function getByCategory($category)
    {
        try {
            $services = Service::byCategory($category)->get();

            return response()->json([
                'success' => true,
                'data' => $services,
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching services by category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch services by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all categories.
     */
    public function getCategories()
    {
        try {
            $categories = Service::select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching service categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
