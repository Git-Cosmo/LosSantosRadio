<?php

namespace Tests\Unit;

use App\Services\AzuraCastService;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AzuraCastServiceTest extends TestCase
{
    private AzuraCastService $service;

    private ReflectionMethod $extractItemsMethod;

    protected function setUp(): void
    {
        parent::setUp();
        // Create service without calling constructor to avoid config dependency
        $this->service = new class extends AzuraCastService
        {
            public function __construct()
            {
                // Skip parent constructor to avoid config dependency
            }
        };

        // Use reflection to access the protected extractItems method
        $this->extractItemsMethod = new ReflectionMethod(AzuraCastService::class, 'extractItems');
        $this->extractItemsMethod->setAccessible(true);
    }

    private function extractItems(array $data): array
    {
        return $this->extractItemsMethod->invoke($this->service, $data);
    }

    public function test_extract_items_from_plain_array(): void
    {
        $data = [
            ['id' => '1', 'title' => 'Song 1'],
            ['id' => '2', 'title' => 'Song 2'],
        ];

        $result = $this->extractItems($data);

        $this->assertEquals($data, $result);
    }

    public function test_extract_items_from_paginated_response_with_items_key(): void
    {
        $items = [
            ['id' => '1', 'title' => 'Song 1'],
            ['id' => '2', 'title' => 'Song 2'],
        ];

        $data = [
            'meta' => ['current_page' => 1, 'total' => 100],
            'items' => $items,
            'links' => ['next' => '/api/station/1/requests?page=2'],
        ];

        $result = $this->extractItems($data);

        $this->assertEquals($items, $result);
    }

    public function test_extract_items_from_paginated_response_with_data_key(): void
    {
        $items = [
            ['id' => '1', 'title' => 'Song 1'],
            ['id' => '2', 'title' => 'Song 2'],
        ];

        $data = [
            'meta' => ['current_page' => 1, 'total' => 100],
            'data' => $items,
            'links' => ['next' => '/api/station/1/requests?page=2'],
        ];

        $result = $this->extractItems($data);

        $this->assertEquals($items, $result);
    }

    public function test_extract_items_returns_empty_array_for_paginated_response_without_items(): void
    {
        $data = [
            'meta' => ['current_page' => 1, 'total' => 0],
            'links' => [],
        ];

        $result = $this->extractItems($data);

        $this->assertEquals([], $result);
    }

    public function test_extract_items_returns_empty_array_for_empty_input(): void
    {
        $result = $this->extractItems([]);

        $this->assertEquals([], $result);
    }

    public function test_extract_items_handles_mixed_content_array(): void
    {
        // Simulates the scenario where API returns unexpected data format
        $data = [
            0 => ['id' => '1', 'title' => 'Song 1'],
            1 => 123, // Integer value that should be filtered later
            2 => ['id' => '2', 'title' => 'Song 2'],
        ];

        $result = $this->extractItems($data);

        // extractItems returns the array as-is; filtering happens in the calling methods
        $this->assertEquals($data, $result);
    }
}
