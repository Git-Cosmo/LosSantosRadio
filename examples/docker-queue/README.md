# Docker Queue Configuration Examples

This directory contains Docker Compose examples for running Los Santos Radio with different queue configurations compatible with Laravel 12.

## Available Configurations

### 1. Redis Queue (`docker-compose.redis.yml`)

Uses Redis as the queue backend. Redis is fast, efficient, and recommended for production environments with high queue volume.

**Features:**
- Redis 7 (Alpine) for queue and cache
- MySQL 8.0 for database
- Single queue worker with auto-restart
- Persistent data volumes for Redis and MySQL
- Optional Laravel Horizon support (commented out)

**Pros:**
- Fast and efficient
- Better for high-volume queues
- Supports advanced features like delayed jobs
- Can be monitored with Laravel Horizon

**Cons:**
- Requires Redis server
- Slightly more complex setup

### 2. Database Queue (`docker-compose.database.yml`)

Uses the database (MySQL) as the queue backend. Simple setup with no additional services required.

**Features:**
- MySQL 8.0 for both database and queue
- Two queue workers processing different queues
- Worker 1: Processes `default` queue
- Worker 2: Processes `high` and `default` queues (priority order)
- Optional scheduler for running scheduled tasks
- Persistent data volume for MySQL

**Pros:**
- Simple setup
- No additional services required
- Good for low to medium volume queues
- Easy to debug (queue jobs visible in database)

**Cons:**
- Slower than Redis for high-volume queues
- Database overhead

## Usage

### Prerequisites

1. Docker and Docker Compose installed
2. Copy the appropriate `.env` configuration

### Starting Redis Queue Setup

```bash
# Navigate to the examples directory
cd examples/docker-queue

# Copy the appropriate Dockerfile to project root (if not already there)
cp Dockerfile ../../Dockerfile

# Start the services
docker-compose -f docker-compose.redis.yml up -d

# View logs
docker-compose -f docker-compose.redis.yml logs -f

# Stop the services
docker-compose -f docker-compose.redis.yml down
```

### Starting Database Queue Setup

```bash
# Navigate to the examples directory
cd examples/docker-queue

# Start the services
docker-compose -f docker-compose.database.yml up -d

# View logs
docker-compose -f docker-compose.database.yml logs -f

# Stop the services
docker-compose -f docker-compose.database.yml down
```

## Initial Setup

After starting the containers for the first time, you need to run migrations:

```bash
# For Redis setup
docker exec -it lsr_app php artisan migrate --force
docker exec -it lsr_app php artisan db:seed --force

# For Database setup
docker exec -it lsr_app_db_queue php artisan migrate --force
docker exec -it lsr_app_db_queue php artisan db:seed --force
```

## Queue Worker Commands

### Monitoring Queue Workers

```bash
# Check worker status (Redis)
docker logs lsr_queue_redis

# Check worker status (Database)
docker logs lsr_queue_worker_1
docker logs lsr_queue_worker_2
```

### Manually Processing Jobs

```bash
# Process one job from the queue (Redis)
docker exec -it lsr_app php artisan queue:work redis --once

# Process one job from the queue (Database)
docker exec -it lsr_app_db_queue php artisan queue:work database --once
```

### Restarting Queue Workers

```bash
# Gracefully restart Redis workers
docker exec -it lsr_queue_redis php artisan queue:restart

# Gracefully restart Database workers
docker exec -it lsr_queue_worker_1 php artisan queue:restart
docker exec -it lsr_queue_worker_2 php artisan queue:restart

# Or restart containers
docker-compose -f docker-compose.redis.yml restart queue_redis
docker-compose -f docker-compose.database.yml restart queue_worker_1 queue_worker_2
```

## Scaling Queue Workers

### Redis Queue

To add more workers, add additional services to `docker-compose.redis.yml`:

```yaml
queue_redis_2:
  build:
    context: ../..
    dockerfile: Dockerfile
  container_name: lsr_queue_redis_2
  restart: unless-stopped
  working_dir: /var/www
  volumes:
    - ../../:/var/www
  command: php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
  environment:
    # ... same as queue_redis
  depends_on:
    - db
    - redis
  networks:
    - lsr_network
```

### Database Queue

The example already includes two workers. Worker 2 prioritizes the `high` queue before processing `default` queue jobs.

To add more workers, duplicate the `queue_worker_1` or `queue_worker_2` service with a new name.

## Queue Configuration in Laravel

### Dispatching Jobs to Queues

```php
// Dispatch to default queue
ProcessPodcast::dispatch($podcast);

// Dispatch to high priority queue
ProcessPodcast::dispatch($podcast)->onQueue('high');

// Dispatch with delay
ProcessPodcast::dispatch($podcast)->delay(now()->addMinutes(5));
```

### Environment Variables

Update your `.env` file based on your choice:

#### Redis Queue Configuration
```env
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PORT=6379
CACHE_STORE=redis
```

#### Database Queue Configuration
```env
QUEUE_CONNECTION=database
CACHE_STORE=database
```

## Monitoring and Management

### Laravel Horizon (Redis Only)

To enable Horizon for advanced Redis queue monitoring:

1. Install Horizon:
```bash
docker exec -it lsr_app composer require laravel/horizon
docker exec -it lsr_app php artisan horizon:install
```

2. Uncomment the `horizon` service in `docker-compose.redis.yml`

3. Restart services:
```bash
docker-compose -f docker-compose.redis.yml up -d
```

4. Access Horizon dashboard at: `http://your-app-url/horizon`

### Database Queue Tables

When using database queue, jobs are stored in the `jobs` table. Monitor with:

```bash
# Check pending jobs
docker exec -it lsr_mysql_db_queue mysql -u laravel -psecret laravel -e "SELECT * FROM jobs;"

# Check failed jobs
docker exec -it lsr_mysql_db_queue mysql -u laravel -psecret laravel -e "SELECT * FROM failed_jobs;"
```

## Troubleshooting

### Queue Workers Not Processing Jobs

1. Check if workers are running:
```bash
docker ps | grep queue
```

2. Check worker logs for errors:
```bash
docker logs lsr_queue_redis
# or
docker logs lsr_queue_worker_1
```

3. Ensure queue connection is configured correctly in `.env`

4. Restart queue workers:
```bash
docker-compose -f docker-compose.redis.yml restart queue_redis
```

### Failed Jobs

View failed jobs:
```bash
docker exec -it lsr_app php artisan queue:failed
```

Retry all failed jobs:
```bash
docker exec -it lsr_app php artisan queue:retry all
```

Retry specific failed job:
```bash
docker exec -it lsr_app php artisan queue:retry {job-id}
```

Clear failed jobs:
```bash
docker exec -it lsr_app php artisan queue:flush
```

### Performance Optimization

For high-volume queues, adjust worker parameters:

```bash
# More aggressive processing
php artisan queue:work redis --sleep=1 --tries=3 --timeout=60 --max-jobs=1000

# Process multiple jobs in parallel (requires multiple workers)
docker-compose -f docker-compose.redis.yml up -d --scale queue_redis=5
```

## Production Recommendations

1. **Use Redis** for better performance and features
2. **Monitor queue workers** with Laravel Horizon or custom monitoring
3. **Set up proper logging** and error tracking (Sentry)
4. **Use multiple workers** for different queue priorities
5. **Implement queue rate limiting** for external API calls
6. **Set realistic timeout values** based on job complexity
7. **Monitor database/Redis performance** and scale accordingly

## Additional Resources

- [Laravel Queue Documentation](https://laravel.com/docs/12.x/queues)
- [Laravel Horizon Documentation](https://laravel.com/docs/12.x/horizon)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Redis Documentation](https://redis.io/documentation)

## Support

For issues or questions about Los Santos Radio, please open an issue on GitHub.
