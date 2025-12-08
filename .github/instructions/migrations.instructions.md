---
applyTo: "database/migrations/**"
---

# Database Migration Instructions

## Database Compatibility

This project supports SQLite, MySQL, and PostgreSQL. Always write migrations that work across all three databases.

## Migration Structure

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            // Schema definition
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

## Common Column Types

```php
Schema::create('users', function (Blueprint $table) {
    $table->id(); // Auto-incrementing BIGINT primary key
    $table->string('name'); // VARCHAR(255)
    $table->string('email')->unique(); // VARCHAR with unique constraint
    $table->text('bio')->nullable(); // TEXT, nullable
    $table->integer('count')->default(0); // INTEGER with default
    $table->boolean('is_active')->default(true); // BOOLEAN
    $table->decimal('price', 8, 2); // DECIMAL(8,2)
    $table->json('metadata')->nullable(); // JSON column
    $table->timestamp('verified_at')->nullable();
    $table->timestamps(); // created_at and updated_at
    $table->softDeletes(); // deleted_at for soft deletes
});
```

## Foreign Keys

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    // Creates: foreign key to users.id, cascades on delete
    
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    // Sets to NULL when parent is deleted
    
    $table->timestamps();
});
```

## Indexes

```php
Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->string('slug')->unique(); // Unique index
    $table->string('title');
    $table->foreignId('category_id')->constrained();
    $table->timestamps();
    
    // Regular index on single column
    $table->index('title');
    
    // Composite index on multiple columns
    $table->index(['category_id', 'created_at']);
    
    // Unique composite index
    $table->unique(['slug', 'category_id']);
});
```

## Filtered/Partial Indexes (PostgreSQL & SQLite Only)

Filtered indexes with WHERE clauses are only supported in PostgreSQL and SQLite 3.8.0+, NOT in MySQL/MariaDB.

```php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Schema::create('likes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('ip_address')->nullable();
    $table->timestamps();
    
    // Regular unique constraint for authenticated users
    $table->unique(['item_id', 'user_id']);
    
    // Regular index for IP queries
    $table->index(['item_id', 'ip_address']);
});

// Add filtered index for guest likes (outside Schema builder)
$driver = DB::connection()->getDriverName();

if (in_array($driver, ['pgsql', 'sqlite'])) {
    try {
        DB::statement('CREATE UNIQUE INDEX likes_item_ip_null_user ON likes(item_id, ip_address) WHERE user_id IS NULL');
    } catch (\Illuminate\Database\QueryException $e) {
        $errorMessage = strtolower($e->getMessage());
        
        if (str_contains($errorMessage, 'syntax error') || str_contains($errorMessage, "near 'where'")) {
            Log::info("Skipped filtered index - not supported on this {$driver} version");
        } else {
            throw $e; // Re-throw unexpected errors
        }
    }
}
```

## Modifying Tables

```php
Schema::table('users', function (Blueprint $table) {
    // Add new column
    $table->string('phone')->nullable()->after('email');
    
    // Modify existing column
    $table->string('name', 100)->change();
    
    // Rename column
    $table->renameColumn('old_name', 'new_name');
    
    // Drop column
    $table->dropColumn('old_column');
    
    // Add index
    $table->index('email');
    
    // Drop index (specify exact index name)
    $table->dropIndex('users_email_index');
});
```

## Updating Existing Data in Migrations

Use `chunk()` instead of `each()` for large datasets to prevent memory issues:

```php
public function up(): void
{
    // Add new column
    Schema::table('users', function (Blueprint $table) {
        $table->string('slug')->nullable();
    });
    
    // Populate the column using chunk() for memory efficiency
    DB::table('users')->orderBy('id')->chunk(1000, function ($users) {
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['slug' => Str::slug($user->name)]);
        }
    });
    
    // Make the column non-nullable after population
    Schema::table('users', function (Blueprint $table) {
        $table->string('slug')->nullable(false)->change();
    });
}
```

## Testing Rollbacks

Always test that your down() method works:

```php
public function down(): void
{
    // For create: drop the table
    Schema::dropIfExists('table_name');
    
    // For modify: reverse the changes
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('new_column');
    });
    
    // For adding filtered index: it will be dropped automatically
    // when table is dropped or can be explicitly dropped:
    // DB::statement('DROP INDEX IF EXISTS index_name');
}
```

## Common Pitfalls to Avoid

### DON'T use database-specific features without driver detection
```php
// Bad: JSON_EXTRACT works in MySQL but not SQLite
DB::raw("JSON_EXTRACT(metadata, '$.key')");

// Good: Use Laravel's query builder which handles differences
$query->whereJsonContains('metadata->key', $value);
```

### DON'T use `each()` on large tables
```php
// Bad: Loads all records into memory
User::each(function ($user) {
    // Process user
});

// Good: Processes in batches
User::chunk(1000, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});
```

### DON'T forget to handle nullable foreign keys
```php
// Bad: Will fail when parent is deleted
$table->foreignId('user_id')->constrained();

// Good: Specify behavior
$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
// or
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

## Migration Naming

Follow Laravel conventions:

- Create table: `create_posts_table`
- Add column: `add_slug_to_posts_table`
- Modify column: `modify_title_in_posts_table`
- Add index: `add_index_to_posts_table`
- Create pivot table: `create_post_tag_table`

## Example: Complete Migration with Best Practices

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Composite indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['category_id', 'is_published']);
        });
        
        // Filtered index for published posts only (PostgreSQL/SQLite)
        $driver = DB::connection()->getDriverName();
        
        if (in_array($driver, ['pgsql', 'sqlite'])) {
            try {
                DB::statement('CREATE INDEX posts_published_idx ON posts(published_at) WHERE is_published = true');
            } catch (\Illuminate\Database\QueryException $e) {
                $errorMessage = strtolower($e->getMessage());
                
                if (str_contains($errorMessage, 'syntax error') || str_contains($errorMessage, "near 'where'")) {
                    Log::info("Skipped filtered index for posts table - not supported on this {$driver} version");
                } else {
                    throw $e;
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```
