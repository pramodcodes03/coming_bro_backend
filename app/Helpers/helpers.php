<?php

if (! function_exists('storage_url')) {
    /**
     * Get the correct URL for an image path.
     * Handles both full external URLs (Firebase, etc.) and local storage paths.
     */
    function storage_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Already a full URL (Firebase, S3, etc.)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . $path);
    }
}
