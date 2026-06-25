<?php

namespace App\Http\Controllers;

use App\Models\DocumentChunk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $file = $request->file('pdf');

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file->getRealPath());

        $text = $pdf->getText();

        $document = new Document;
        $document->title = $request->input('title');
        $document->save();

        $chunks = $this->chunkText($text);

        foreach ($chunks as $chunk) {
            $documentChunk = new DocumentChunk;
            $documentChunk->document_id = $document->id;
            $documentChunk->text = $chunk;
            $documentChunk->save();
        }

        return response()->json($document, 201);
    }
    
    /**
     * @return string[]
     */
    public function chunkText(string $text, int $maxChunkSize = 2000): array
    {
        $blocks = explode("\n", $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($blocks as $block) {
            if (Str::length($currentChunk) + Str::length($block) > $maxChunkSize) {
                if (!empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                }

                $currentChunk = $block;
            } else {
                $currentChunk .= "\n" . $block;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }
}
