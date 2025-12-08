# Integration Testing with AzuraCast

## Overview

Los Santos Radio includes integration tests that can run against a real AzuraCast instance. These tests verify that the application correctly integrates with the AzuraCast API.

## Configuration

### Local Development

To run integration tests locally, set the following environment variables:

```bash
export AZURACAST_BASE_URL=https://radio.lossantosradio.com
export AZURACAST_API_KEY=your-api-key-here
export AZURACAST_STATION_ID=1
```

Or add them to your `.env` file:

```env
AZURACAST_BASE_URL=https://radio.lossantosradio.com
AZURACAST_API_KEY=your-api-key-here
AZURACAST_STATION_ID=1
```

### GitHub Actions CI

Integration tests are automatically run in CI when the repository secrets are configured:

1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Add the following secret:
   - `AZURACAST_API_KEY`: Your AzuraCast API key

The workflow automatically uses:
- `AZURACAST_BASE_URL=https://radio.lossantosradio.com`
- `AZURACAST_STATION_ID=1`

These values are configured in `.github/workflows/ci.yml`.

## Running Integration Tests

### Run All Tests (Including Integration)

```bash
php artisan test
```

### Run Only Integration Tests

```bash
php artisan test tests/Feature/AzuraCastIntegrationTest.php
```

### Run Specific Integration Test

```bash
php artisan test --filter test_can_fetch_now_playing_data
```

## Test Behavior

### Without API Credentials

If `AZURACAST_API_KEY` is not set, integration tests will be automatically skipped:

```
Tests:    9 skipped (AzuraCast API credentials not configured)
```

### With API Credentials

When credentials are provided, tests will:

1. ✅ Fetch now playing data
2. ✅ Fetch station information
3. ✅ Fetch song history
4. ✅ Fetch requestable songs
5. ✅ Search requestable songs
6. ✅ Fetch request queue
7. ✅ Fetch playlists
8. ✅ Test error handling with invalid credentials
9. ✅ Verify caching functionality

## Available Integration Tests

### `AzuraCastIntegrationTest`

Located in `tests/Feature/AzuraCastIntegrationTest.php`, this test suite includes:

#### Data Fetching Tests
- **test_can_fetch_now_playing_data**: Verifies now playing endpoint returns expected structure
- **test_can_fetch_station_info**: Validates station metadata retrieval
- **test_can_fetch_song_history**: Tests song history endpoint
- **test_can_fetch_requestable_songs**: Validates song request library
- **test_can_search_requestable_songs**: Tests search functionality
- **test_can_fetch_request_queue**: Verifies current request queue
- **test_can_fetch_playlists**: Tests playlist endpoint

#### Reliability Tests
- **test_handles_api_errors_gracefully**: Validates error handling with invalid credentials
- **test_caching_works_for_now_playing**: Ensures caching mechanism works correctly

## Troubleshooting

### Tests Skip with "API credentials not configured"

**Solution**: Set the `AZURACAST_API_KEY` environment variable.

```bash
export AZURACAST_API_KEY=your-api-key-here
php artisan test tests/Feature/AzuraCastIntegrationTest.php
```

### Tests Fail with "Connection timeout"

**Possible Causes**:
1. AzuraCast instance is down or unreachable
2. API key is invalid
3. Network connectivity issues
4. Firewall blocking requests

**Solution**: Verify the AzuraCast instance is accessible:

```bash
curl -H "X-API-Key: your-api-key" https://radio.lossantosradio.com/api/nowplaying
```

### Tests Fail with "Unauthorized" (401)

**Cause**: Invalid or expired API key.

**Solution**: Generate a new API key from your AzuraCast admin panel:
1. Log in to AzuraCast
2. Go to **Administration** → **API Keys**
3. Create a new API key
4. Update your environment variables or secrets

### Cached Data Interfering with Tests

**Solution**: Clear the cache before running tests:

```bash
php artisan cache:clear
php artisan test tests/Feature/AzuraCastIntegrationTest.php
```

## Best Practices

### 1. Use Separate API Keys for Testing

Create a dedicated API key for CI/testing to:
- Monitor usage separately
- Revoke easily if needed
- Set appropriate rate limits

### 2. Test Against Staging Environment

If available, configure tests to use a staging AzuraCast instance:

```env
AZURACAST_BASE_URL=https://staging.radio.lossantosradio.com
```

### 3. Rate Limiting Awareness

Integration tests make real API calls. Be mindful of:
- AzuraCast rate limits
- Network bandwidth
- API quotas

### 4. CI Configuration

For CI environments:
- Store API key as a repository secret
- Never commit API keys to version control
- Use different credentials for CI vs production

## Security Notes

⚠️ **Important Security Considerations**:

1. **Never commit API keys** to the repository
2. **Use GitHub Secrets** for CI/CD pipelines
3. **Rotate keys regularly** as a security best practice
4. **Use read-only API keys** when possible for testing
5. **Monitor API usage** for unexpected activity

## Configuration Files

### phpunit.xml

```xml
<env name="AZURACAST_BASE_URL" value="https://radio.lossantosradio.com" force="true"/>
<env name="AZURACAST_API_KEY" value="" force="true"/>
<env name="AZURACAST_STATION_ID" value="1" force="true"/>
```

These defaults are overridden by environment variables when set.

### .github/workflows/ci.yml

```yaml
- name: Run tests
  run: php artisan test --parallel
  env:
    AZURACAST_BASE_URL: https://radio.lossantosradio.com
    AZURACAST_API_KEY: ${{ secrets.AZURACAST_API_KEY }}
    AZURACAST_STATION_ID: 1
```

## Contributing

When adding new AzuraCast integration tests:

1. Follow the existing test structure
2. Add `setUp()` method that skips if credentials not available
3. Test both success and error scenarios
4. Document the test purpose clearly
5. Ensure tests are idempotent (can run multiple times)

## Support

For issues with integration testing:
- Check AzuraCast API documentation: https://www.azuracast.com/api/
- Review test logs for detailed error messages
- Verify API key permissions in AzuraCast admin panel
- Contact the development team via GitHub issues

---

**Last Updated**: December 8, 2025  
**Related Files**:
- `tests/Feature/AzuraCastIntegrationTest.php`
- `.github/workflows/ci.yml`
- `phpunit.xml`
