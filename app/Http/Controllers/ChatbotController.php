<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InputAspirasi;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('admin.chatbot');
    }

    public function tanya(Request $request)
    {
        $pesanUser = $request->input('pesan');
        $apiKey = env('GROQ_API_KEY');

        // 1. Ambil Data Real-time dari Database
        $total = InputAspirasi::count();
        $pending = InputAspirasi::where('status', 'Pending')->count();
        $proses = InputAspirasi::where('status', 'Diproses')->count();
        $selesai = InputAspirasi::where('status', 'Selesai')->count();

        // 2. Setting Instruksi AI (System Prompt)
        $systemPrompt = "Anda adalah AI SAPRAS, asisten cerdas inventaris sekolah. 
        Data saat ini: Total Laporan=$total, Pending=$pending, Diproses=$proses, Selesai=$selesai. 
        Tugas: Jawab pertanyaan admin dengan ramah dan sangat singkat hanya berdasarkan data tersebut.";

        try {
            /**
             * PERUBAHAN PENTING:
             * Model diubah dari 'llama3-8b-8192' menjadi 'llama-3.1-8b-instant'
             * Ini adalah model pengganti yang lebih cepat dan pintar.
             */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post('https://api.groq.com/openai/v1/chat/completions', [
                "model" => "llama-3.1-8b-instant", 
                "messages" => [
                    ["role" => "system", "content" => $systemPrompt],
                    ["role" => "user", "content" => $pesanUser]
                ],
                "temperature" => 0.5 
            ]);

            $hasil = $response->json();

            // 3. Penanganan Error
            if (empty($apiKey)) {
                return response()->json(['jawaban' => "Error: API Key kosong di file .env!"]);
            }

            if (isset($hasil['error'])) {
                return response()->json(['jawaban' => "Groq Error: " . $hasil['error']['message']]);
            }

            if (isset($hasil['choices'][0]['message']['content'])) {
                $jawabanAI = $hasil['choices'][0]['message']['content'];
            } else {
                $jawabanAI = "Error: Format respon tidak dikenal.";
            }

            return response()->json(['jawaban' => $jawabanAI]);

        } catch (\Exception $e) {
            return response()->json(['jawaban' => 'Koneksi Error: ' . $e->getMessage()], 500);
        }
    }
}