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
     * Note: These user agents represent common browser versions as of late 2024.
     * For best results, these should be periodically updated to reflect current
     * browser versions. You can use addUserAgents() or setUserAgents() to
     * customize the list, or override this array in a service provider.
     */
    protected array $userAgents = [
        // Chrome on Windows
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',

        // Chrome on Mac
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',

        // Firefox on Windows
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:119.0) Gecko/20100101 Firefox/119.0',

        // Firefox on Mac
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:120.0) Gecko/20100101 Firefox/120.0',

        // Safari on Mac
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Safari/605.1.15',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',

        // Edge on Windows
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0',

        // Chrome on Linux
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',

        // Firefox on Linux
        'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (X11; Linux x86_64; rv:120.0) Gecko/20100101 Firefox/120.0',

        // Mobile Chrome on Android
        'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36',
        'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36',

        // Safari on iOS
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (iPad; CPU OS 17_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Mobile/15E148 Safari/604.1',
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
     */
    public function postJson(string $url, array $data, array $options = []): ?array
    {
        $options['json'] = $data;
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response = $this->post($url, $options);

        if (! $response) {
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
