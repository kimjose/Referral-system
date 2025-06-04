<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ECHISOutboundService
{
    protected $config;
    protected $webhookUrl;
    protected $authConfig;

    public function __construct()
    {
        $this->config = Config::get('services.echis.outbound');
        $this->webhookUrl = $this->config['webhook_url'];
        $this->authConfig = $this->config['auth'];
    }

    /**
     * Process and send outbound data to eCHIS
     *
     * @param array $data The data to be sent
     * @return array Response with success status and message
     */
    public function processOutbound($data)
    {
        if (!$this->config['enabled']) {
            Log::info('eCHIS outbound is disabled');
            return [
                'success' => false,
                'message' => 'eCHIS outbound is disabled'
            ];
        }

        try {
            $mappedData = $this->mapData($data);
            $headers = $this->getAuthHeaders();

            $response = Http::withHeaders($headers)
                ->post($this->webhookUrl, $mappedData);

            if ($response->successful()) {
                Log::info('Successfully sent data to eCHIS', [
                    'data' => $mappedData,
                    'response' => $response->json()
                ]);

                return [
                    'success' => true,
                    'message' => 'Data successfully sent to eCHIS',
                    'data' => $response->json()
                ];
            }

            Log::error('Failed to send data to eCHIS', [
                'data' => $mappedData,
                'response' => $response->json()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send data to eCHIS: ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Error processing eCHIS outbound', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error processing eCHIS outbound: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Map the data according to the configuration
     *
     * @param array $data The original data
     * @return array The mapped data
     */
    protected function mapData($data)
    {
        $mappedData = [];
        foreach ($this->config['mapping'] as $key => $path) {
            $value = $this->getValueFromPath($data, $path);
            if ($value !== null) {
                $mappedData[$key] = $value;
            }
        }
        return $mappedData;
    }

    /**
     * Get value from data using dot notation path
     *
     * @param array $data The data array
     * @param string $path The dot notation path
     * @return mixed The value or null if not found
     */
    protected function getValueFromPath($data, $path)
    {
        $keys = explode('.', $path);
        $value = $data;

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }
            $value = $value[$key];
        }

        return $value;
    }

    /**
     * Get authentication headers based on configuration
     *
     * @return array The authentication headers
     */
    protected function getAuthHeaders()
    {
        $headers = [];

        if ($this->authConfig['type'] === 'header') {
            $headers[$this->authConfig['name']] = Config::get('services.echis.' . $this->authConfig['value_key']);
        }

        return $headers;
    }
} 