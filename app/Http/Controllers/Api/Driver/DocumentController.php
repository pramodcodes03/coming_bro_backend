<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DriverDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Get all enabled documents (where enable=true, is_deleted=false).
     */
    public function index(): JsonResponse
    {
        $documents = Document::where('enable', true)
            ->where('is_deleted', false)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Documents retrieved successfully.',
            'data' => $documents,
        ]);
    }

    /**
     * Get document info by ID.
     */
    public function show(string $id): JsonResponse
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Document retrieved successfully.',
            'data' => $document,
        ]);
    }

    /**
     * Get current driver's uploaded documents.
     */
    public function driverDocuments(Request $request): JsonResponse
    {
        $driver = $request->user();

        $driverDocument = DriverDocument::where('driver_id', $driver->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Driver documents retrieved successfully.',
            'data' => $driverDocument,
        ]);
    }

    /**
     * Get driver document numbers.
     */
    public function documentNumbers(Request $request): JsonResponse
    {
        $driver = $request->user();

        $driverDocument = DriverDocument::where('driver_id', $driver->id)->first();

        $numbers = [];
        if ($driverDocument && is_array($driverDocument->documents)) {
            foreach ($driverDocument->documents as $doc) {
                if (isset($doc['document_number'])) {
                    $numbers[] = [
                        'document_id' => $doc['document_id'] ?? null,
                        'document_number' => $doc['document_number'],
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Document numbers retrieved successfully.',
            'data' => $numbers,
        ]);
    }

    /**
     * Upload/update driver document.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'document_id' => 'required|string',
            'front_image' => 'nullable|string',
            'back_image' => 'nullable|string',
            'document_number' => 'nullable|string',
            'expiry_date' => 'nullable|string',
        ]);

        $driver = $request->user();

        $driverDocument = DriverDocument::where('driver_id', $driver->id)->first();

        $newDoc = [
            'document_id' => $request->document_id,
            'front_image' => $request->front_image,
            'back_image' => $request->back_image,
            'document_number' => $request->document_number,
            'expiry_date' => $request->expiry_date,
            'status' => 'pending',
        ];

        if ($driverDocument) {
            $documents = $driverDocument->documents ?? [];
            $updated = false;

            // Update existing document entry or add new one
            foreach ($documents as $index => $doc) {
                if (isset($doc['document_id']) && $doc['document_id'] === $request->document_id) {
                    $documents[$index] = array_merge($doc, $newDoc);
                    $updated = true;
                    break;
                }
            }

            if (!$updated) {
                $documents[] = $newDoc;
            }

            $driverDocument->documents = $documents;
            $driverDocument->save();
        } else {
            $driverDocument = DriverDocument::create([
                'driver_id' => $driver->id,
                'documents' => [$newDoc],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully.',
            'data' => $driverDocument->fresh(),
        ]);
    }
}
