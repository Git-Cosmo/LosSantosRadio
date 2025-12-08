---
applyTo: "routes/api.php"
applyTo: "app/Http/Controllers/Api/**"
---

# API Development Instructions

## API Design Principles

- Follow RESTful conventions
- Use proper HTTP status codes
- Return consistent JSON responses
- Include pagination for list endpoints
- Add proper API documentation comments

## Response Structure

All API responses should follow this structure:

```php
// Success response
return response()->json([
    'data' => $data,
    'meta' => [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
    ]
], 200);

// Error response
return response()->json([
    'message' => 'Error description',
    'errors' => $validationErrors, // Optional
], 422);
```

## Pagination

Use Laravel's pagination helpers:

```php
$games = Game::query()
    ->when($request->platform, fn($q, $v) => $q->where('platform', $v))
    ->paginate($request->get('per_page', 15));

return response()->json($games);
```

## Validation

Always use Form Requests or inline validation:

```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'platform' => 'nullable|string|in:pc,ps5,xbox',
]);
```

## API Routes

API routes are exempt from CSRF protection but should use other security measures:
- Rate limiting
- API token authentication for sensitive endpoints
- Input validation

## Common Patterns

### List Endpoint
```php
public function index(Request $request)
{
    $perPage = min($request->get('per_page', 15), 100);
    
    $items = Model::query()
        ->when($request->search, fn($q, $v) => $q->where('title', 'like', "%{$v}%"))
        ->when($request->filter, fn($q, $v) => $q->where('category', $v))
        ->latest()
        ->paginate($perPage);
    
    return response()->json($items);
}
```

### Show Endpoint
```php
public function show(string $slug)
{
    $item = Model::where('slug', $slug)->firstOrFail();
    return response()->json(['data' => $item]);
}
```

### Search Endpoint
```php
public function search(Request $request)
{
    $request->validate([
        'q' => 'required|string|min:2',
        'per_page' => 'nullable|integer|min:1|max:100',
    ]);
    
    $results = Model::search($request->q)
        ->paginate($request->get('per_page', 15));
    
    return response()->json($results);
}
```

## Error Handling

Handle common errors gracefully:

```php
try {
    $data = $this->service->fetchData();
    return response()->json(['data' => $data]);
} catch (NotFoundException $e) {
    return response()->json(['message' => 'Resource not found'], 404);
} catch (ServiceException $e) {
    Log::error('API error', ['error' => $e->getMessage()]);
    return response()->json(['message' => 'Service unavailable'], 503);
}
```
