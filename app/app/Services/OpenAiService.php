<?php

namespace App\Services;

class OpenAiService
{
    const METHOD = 'POST';
    const CONTENT_TYPE = 'Content-Type: application/json\r\nAuthorization: Bearer ';
    const MODEL = 'text-embedding-3-small';
    const API_URL = 'https://api.openai.com/v1/embeddings';

    protected $apiKey;
    
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function convertTextToVector(string $text): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => self::METHOD,
                'header' => self::CONTENT_TYPE . $this->apiKey,
                'content' => json_encode([
                    'model' => self::MODEL,
                    'input' => $text
                ]),
            ]
        ]);

        $response = file_get_contents(self::API_URL, false, $context);
        return json_decode($response, true)['data'][0]['embedding'];
    }

    public function getResponseInNaturalLanguage(string $prompt): array
    {
        // TODO: implementar la respuesta en lenguaje natural

        return [];
    }
}
