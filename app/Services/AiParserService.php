<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

class AiParserService
{
    protected string $provider;
    protected string $apiKey;

    public function __construct()
    {
        $this->provider = config('services.ai_parser.provider', 'openai');
        $this->apiKey = config('services.ai_parser.api_key', '');
    }

    public function extractFromImage(Request $request): array
    {
        $request->validate([
            'id_document' => 'required|image|max:5120',
        ]);

        $image = $request->file('id_document');
        $base64 = base64_encode(file_get_contents($image->getRealPath()));
        $mime = $image->getMimeType();

        if ($this->provider === 'openai') {
            return $this->openaiExtract($base64, $mime);
        }

        return $this->geminiExtract($base64, $mime);
    }

    protected function openaiExtract(string $base64, string $mime): array
    {
        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => 'Extract the full name and RUT number from this Chilean ID card image. Return ONLY a valid JSON object with keys "name" and "rut". No markdown, no explanation.'],
                            ['type' => 'image_url', 'image_url' => ['url' => "data:$mime;base64,$base64"]],
                        ],
                    ],
                ],
                'max_tokens' => 200,
            ]);

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '{}';

        return $this->parseJson($content);
    }

    protected function geminiExtract(string $base64, string $mime): array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$this->apiKey";

        $response = Http::timeout(60)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Extract the full name and RUT number from this Chilean ID card image. Return ONLY a valid JSON object with keys "name" and "rut". No markdown, no explanation.'],
                        ['inline_data' => ['mime_type' => $mime, 'data' => $base64]],
                    ],
                ],
            ],
        ]);

        $data = $response->json();
        $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        return $this->parseJson($content);
    }

    protected function parseJson(string $content): array
    {
        $content = trim($content);
        $content = preg_replace('/^```json\s*/', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);

        $json = json_decode($content, true);

        return [
            'name' => $json['name'] ?? '',
            'rut' => $json['rut'] ?? '',
        ];
    }
}
