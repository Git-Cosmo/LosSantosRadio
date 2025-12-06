<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;

class HttpClientService
{
    protected Client $client;

    /**
     * User agents to rotate through for making requests appear unique.
     *
     * Note: These user agents represent common browser versions as of December 2025.
     * For best results, these should be periodically updated to reflect current
     * browser versions. You can use addUserAgents() or setUserAgents() to
     * customize the list, or override this array in a service provider.
     */
    protected array $userAgents = [
        // Chrome on Windows (Current: Chrome 131)
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',

        // Chrome on Mac (Current: Chrome 131)
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',

        // Firefox on Windows (Current: Firefox 133)
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:133.0) Gecko/20100101 Firefox/133.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:132.0) Gecko/20100101 Firefox/132.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0',

        // Firefox on Mac (Current: Firefox 133)
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:133.0) Gecko/20100101 Firefox/133.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:132.0) Gecko/20100101 Firefox/132.0',

        // Safari on Mac (Current: Safari 18)
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Safari/605.1.15',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Safari/605.1.15',

        // Edge on Windows (Current: Edge 131)
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',

        // Chrome on Linux (Current: Chrome 131)
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',

        // Firefox on Linux (Current: Firefox 133)
        'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:133.0) Gecko/20100101 Firefox/133.0',
        'Mozilla/5.0 (X11; Linux x86_64; rv:132.0) Gecko/20100101 Firefox/132.0',

        // Mobile Chrome on Android (Current: Chrome 131)
        'Mozilla/5.0 (Linux; Android 14; SM-S928B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.6778.104 Mobile Safari/537.36',
        'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.6778.104 Mobile Safari/537.36',

        // Safari on iOS (Current: iOS 18 / Safari 18)
        'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (iPad; CPU OS 18_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Mobile/15E148 Safari/604.1',
    ];

    protected array $defaultHeaders = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Connection' => 'keep-alive',
        'Upgrade-Insecure-Requests' => '1',
    ];

    public function __construct(?array $config = null)
    {
        $this->initClient($config ?? []);
    }

    /**
     * Initialize the Guzzle client with middleware.
     */
    protected function initClient(array $config): void
    {
        $stack = HandlerStack::create();

        // Add random user agent middleware
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            return $request->withHeader('User-Agent', $this->getRandomUserAgent());
        }));

        // Merge with default config
        $defaultConfig = [
            'handler' => $stack,
            'timeout' => 30,
            'connect_timeout' => 10,
            'http_errors' => false,
            'headers' => $this->defaultHeaders,
            'verify' => true,
        ];

        $this->client = new Client(array_merge($defaultConfig, $config));
    }

    /**
     * Get a random user agent from the list.
     */
    public function getRandomUserAgent(): string
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    /**
     * Set custom user agents.
     */
    public function setUserAgents(array $userAgents): self
    {
        $this->userAgents = $userAgents;

        return $this;
    }

    /**
     * Add user agents to the existing list.
     */
    public function addUserAgents(array $userAgents): self
    {
        $this->userAgents = array_merge($this->userAgents, $userAgents);

        return $this;
    }

    /**
     * Make a GET request.
     */
    public function get(string $url, array $options = []): ?Response
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * Make a POST request.
     */
    public function post(string $url, array $options = []): ?Response
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * Make a PUT request.
     */
    public function put(string $url, array $options = []): ?Response
    {
        return $this->request('PUT', $url, $options);
    }

    /**
     * Make a PATCH request.
     */
    public function patch(string $url, array $options = []): ?Response
    {
        return $this->request('PATCH', $url, $options);
    }

    /**
     * Make a DELETE request.
     */
    public function delete(string $url, array $options = []): ?Response
    {
        return $this->request('DELETE', $url, $options);
    }

    /**
     * Make an HTTP request.
     *
     * @param  string  $method  The HTTP method (e.g., 'GET', 'POST', etc.)
     * @param  string  $url  The request URL
     * @param  array  $options  Optional request options
     * @return Response|null Returns the response on success, or null if the request fails.
     *                       Callers should check for null before using the response.
     */
    public function request(string $method, string $url, array $options = []): ?Response
    {
        try {
            $response = $this->client->request($method, $url, $options);

            return $response;
        } catch (RequestException $e) {
            Log::error('HTTP Request failed', [
                'method' => $method,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            if ($e->hasResponse()) {
                return $e->getResponse();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('HTTP Request exception', [
                'method' => $method,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get JSON response from a URL.
     *
     * @return array|null Returns null if request fails or status code >= 400
     */
    public function getJson(string $url, array $options = []): ?array
    {
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Accept' => 'application/json',
        ]);

        $response = $this->get($url, $options);

        if (! $response || $response->getStatusCode() >= 400) {
            return null;
        }

        $body = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    /**
     * Post JSON data to a URL.
     *
     * @return array|null Returns null if request fails or status code >= 400
     */
    public function postJson(string $url, array $data, array $options = []): ?array
    {
        $options['json'] = $data;
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response = $this->post($url, $options);

        if (! $response || $response->getStatusCode() >= 400) {
            return null;
        }

        $body = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    /**
     * Get the underlying Guzzle client.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Create a new instance with specific configuration.
     */
    public static function make(array $config = []): self
    {
        return new self($config);
    }

    /**
     * Create a new instance with a custom base URI.
     */
    public static function withBaseUri(string $baseUri, array $config = []): self
    {
        $config['base_uri'] = $baseUri;

        return new self($config);
    }
}
