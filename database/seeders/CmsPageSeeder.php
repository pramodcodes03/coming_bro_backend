<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('firebase_collection/cms_pages.json');
        $records = json_decode(file_get_contents($jsonPath), true);

        foreach ($records as $record) {
            DB::table('cms_pages')->updateOrInsert(
                ['name' => $record['name'] ?? ''],
                [
                    'name' => $record['name'] ?? null,
                    'slug' => $record['slug'] ?? null,
                    'description' => $record['description'] ?? null,
                    'publish' => $record['publish'] ?? true,
                ]
            );
        }
    }
}
