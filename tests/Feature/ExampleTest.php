<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The marketing homepage reads live `settings` (logo, contact, …), which the
     * empty :memory: SQLite test DB doesn't have. Point at the real MySQL
     * connection (read-only here) so the page renders, mirroring the other
     * feature tests. phpunit.xml sets DB_DATABASE=:memory:, so read the real DB
     * name from .env and purge the cached PDO so it reconnects cleanly.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $database = 'coming_bro';
        $envPath = base_path('.env');
        if (is_file($envPath) && preg_match('/^DB_DATABASE=(.*)$/m', file_get_contents($envPath), $m)) {
            $database = trim($m[1]);
        }

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => $database,
        ]);

        \DB::purge('mysql');
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
