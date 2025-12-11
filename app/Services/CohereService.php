<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CohereService
{
    /**
     * API URL to get embed value
     */
    protected $apiUrl = 'https://api.cohere.com/v1/embed';


    /**
     * Call API and get generated embed text
     */
    public function generateEmbedding(string $text)
    {
        try {
            // Prepare the request payload
            $payload = [
                'texts' => [$text],
                'model' => 'embed-english-v2.0',
                'input_type' => 'classification',
                'truncate' => 'NONE'
            ];

            // Send the request using the Http client
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('COHERE_API_KEY'),
            ])->post($this->apiUrl, $payload);

            // Handle failed response
            if ($response->failed()) {
                return $this->handleError('Failed to get embeddings from Cohere', $response);
            }

            // Parse and check the response for embeddings
            $embeddings = $response->json();
            if (empty($embeddings['embeddings'])) {
                return $this->handleError('No embeddings returned from Cohere', $embeddings);
            }

            // Return the first embedding
            return $embeddings['embeddings'][0];

        } catch (Exception $e) {
            // Log the exception and return null
            $this->logException($e);
            return null;
        }
    }

    /**
     * Handle API error responses by logging and returning null.
     */
    private function handleError($message, $response)
    {
        Log::error($message, ['response' => $response->json()]);
        return null;
    }

    /**
     * Log detailed exception information.
     */
    private function logException(Exception $e)
    {
        Log::error('Exception on generateEmbedding => ', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /**
     * Get comparative value of embed data
     */
    public function cosineSimilarity(array $vec1, array $vec2)
    {
        $dotProduct = 0;
        $magnitudeVec1 = 0;
        $magnitudeVec2 = 0;

        foreach ($vec1 as $i => $value) {
            $dotProduct += $value * $vec2[$i];
            $magnitudeVec1 += $value ** 2;
            $magnitudeVec2 += $vec2[$i] ** 2;
        }

        $magnitudeVec1 = sqrt($magnitudeVec1);
        $magnitudeVec2 = sqrt($magnitudeVec2);

        return $dotProduct / ($magnitudeVec1 * $magnitudeVec2);
    }
}
