<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait StoresBase64Image
{
    protected function storeBase64Image(?string $input, string $directory = 'customer-profile-pics'): ?string
    {
        if (!$input) {
            return null;
        }

        if (Str::startsWith($input, ['http://', 'https://'])) {
            return $input;
        }

        $extension = 'png';
        $data = $input;

        if (preg_match('/^data:image\/(\w+);base64,/', $input, $matches)) {
            $extension = strtolower($matches[1]);
            $data = substr($input, strpos($input, ',') + 1);
        }

        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            return $input;
        }

        $allowed = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        if (!in_array($extension, $allowed, true)) {
            $extension = 'png';
        }

        $path = $directory . '/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($path, $decoded);

        return Storage::disk('public')->url($path);
    }
}
