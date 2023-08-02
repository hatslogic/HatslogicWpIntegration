<?php

namespace HatslogicWpIntegration\Service;

class CurlService
{
    public function getApiResponse(string $url): ?array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? json_decode($response, true) : null;
    }

    public function getApiHeaderResponse(string $url): ?array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_HEADER => true,
        ]);

        $response = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        curl_close($curl);

        if ($response) {
            $responseHeaders = substr($response, 0, $headerSize);
            $responseBody = substr($response, $headerSize);

            $decodedResponse = json_decode($responseBody, true);

            preg_match('/X-WP-Total:\s*(\d+)/i', $responseHeaders, $matches);
            $xWpTotal = isset($matches[1]) ? (int)$matches[1] : 0;

            return [
                'body' => $decodedResponse,
                'xWpTotal' => $xWpTotal,
            ];
        } else {
            return [
                'body' => [],
                'xWpTotal' => 0
            ];
        }
    }

}
