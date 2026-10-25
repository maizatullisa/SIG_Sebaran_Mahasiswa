<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\SIGAService;
use App\Models\Mahasiswa;


class AIController extends Controller
{
    public function index()
    {
        return view('admin.ai.index');
    }

    /**
     * Bangun konteks data real dari database
     * Data sensitif (detail_alamat, lat/lng, nim) TIDAK disertakan
     */
    private function buildDatabaseContext(): string
    {
        $ctx = [];

        // ── 1. TOTAL KESELURUHAN ────────────────────────────────────────
        $totalMahasiswa = DB::table('mahasiswa')->count();
        $totalAlamat    = DB::table('alamat_mahasiswas')->count();
        $totalSekolah   = DB::table('sekolahs')->count();

        $ctx[] = "=== RINGKASAN SISTEM SIG MAHASISWA ===";
        $ctx[] = "Total mahasiswa terdaftar : {$totalMahasiswa} orang";
        $ctx[] = "Total data alamat          : {$totalAlamat} entri";
        $ctx[] = "Total data sekolah asal    : {$totalSekolah} entri";

        // ── 2. SEBARAN PER PRODI ────────────────────────────────────────
        $perProdi = DB::table('mahasiswa')
            ->select('prodi', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('prodi')
            ->groupBy('prodi')
            ->orderByDesc('jumlah')
            ->get();

        if ($perProdi->isNotEmpty()) {
            $ctx[] = "\n=== SEBARAN PER PROGRAM STUDI ===";
            foreach ($perProdi as $p) {
                $pct = $totalMahasiswa > 0
                    ? round(($p->jumlah / $totalMahasiswa) * 100, 1)
                    : 0;
                $ctx[] = "- {$p->prodi}: {$p->jumlah} mahasiswa ({$pct}%)";
            }
        }

        // ── 3. SEBARAN PER MINAT ────────────────────────────────────────
        $perMinat = DB::table('mahasiswa')
            ->select('minat', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('minat')
            ->where('minat', '!=', '')
            ->groupBy('minat')
            ->orderByDesc('jumlah')
            ->get();

        if ($perMinat->isNotEmpty()) {
            $ctx[] = "\n=== SEBARAN PER MINAT / KONSENTRASI ===";
            foreach ($perMinat as $m) {
                $ctx[] = "- {$m->minat}: {$m->jumlah} mahasiswa";
            }
        }

        // ── 4. SEBARAN PER JURUSAN ──────────────────────────────────────
        $perJurusan = DB::table('mahasiswa')
            ->select('jurusan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('jurusan')
            ->where('jurusan', '!=', '')
            ->groupBy('jurusan')
            ->orderByDesc('jumlah')
            ->get();

        if ($perJurusan->isNotEmpty()) {
            $ctx[] = "\n=== SEBARAN PER JURUSAN ===";
            foreach ($perJurusan as $j) {
                $ctx[] = "- {$j->jurusan}: {$j->jumlah} mahasiswa";
            }
        }

        // ── 5. PREDIKSI TREN (dari kolom angkatan jika ada) ─────────────
        if ($this->hasColumn('mahasiswa', 'angkatan')) {
            $perAngkatan = DB::table('mahasiswa')
                ->select('angkatan', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('angkatan')
                ->groupBy('angkatan')
                ->orderBy('angkatan')
                ->get();

            if ($perAngkatan->count() >= 2) {
                $ctx[] = "\n=== TREN PER ANGKATAN ===";
                foreach ($perAngkatan as $a) {
                    $ctx[] = "- Angkatan {$a->angkatan}: {$a->jumlah} mahasiswa";
                }

                $values    = $perAngkatan->pluck('jumlah')->toArray();
                $years     = $perAngkatan->pluck('angkatan')->toArray();
                $growth    = [];
                for ($i = 1; $i < count($values); $i++) {
                    $growth[] = $values[$i] - $values[$i - 1];
                }
                $avgGrowth = round(array_sum($growth) / count($growth));
                $lastYear  = (int) end($years);
                $lastVal   = (int) end($values);

                $ctx[] = "\n=== PREDIKSI ANGKATAN (ESTIMASI TREN LINEAR) ===";
                $ctx[] = "Rata-rata pertumbuhan/tahun: {$avgGrowth} mahasiswa";
                for ($i = 1; $i <= 3; $i++) {
                    $pred = max(0, $lastVal + ($avgGrowth * $i));
                    $ctx[] = "Prediksi angkatan " . ($lastYear + $i) . ": ~{$pred} mahasiswa";
                }
            }
        }

        // ── 6. SEBARAN PROVINSI (join indonesia_provinces) ──────────────
        $perProvinsi = DB::table('alamat_mahasiswas')
            ->join('indonesia_provinces', 'alamat_mahasiswas.provinsi', '=', 'indonesia_provinces.code')
            ->select('indonesia_provinces.name as provinsi', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('indonesia_provinces.name')
            ->orderByDesc('jumlah')
            ->get();

        if ($perProvinsi->isNotEmpty()) {
            $ctx[] = "\n=== SEBARAN MAHASISWA PER PROVINSI ===";
            foreach ($perProvinsi as $p) {
                $ctx[] = "- {$p->provinsi}: {$p->jumlah} mahasiswa";
            }
        }

        // ── 7. TOP 25 KABUPATEN/KOTA ────────────────────────────────────
        $perKabupaten = DB::table('alamat_mahasiswas')
            ->select('kabupaten', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->groupBy('kabupaten')
            ->orderByDesc('jumlah')
            ->limit(25)
            ->get();

        if ($perKabupaten->isNotEmpty()) {
            $ctx[] = "\n=== TOP 25 KABUPATEN/KOTA ASAL MAHASISWA ===";
            foreach ($perKabupaten as $k) {
                $ctx[] = "- {$k->kabupaten}: {$k->jumlah} mahasiswa";
            }
        }

        // ── 8. TOP 20 KECAMATAN ─────────────────────────────────────────
        $perKecamatan = DB::table('alamat_mahasiswas')
            ->select('kecamatan', 'kabupaten', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('kecamatan')
            ->where('kecamatan', '!=', '')
            ->groupBy('kecamatan', 'kabupaten')
            ->orderByDesc('jumlah')
            ->limit(20)
            ->get();

        if ($perKecamatan->isNotEmpty()) {
            $ctx[] = "\n=== TOP 20 KECAMATAN ASAL MAHASISWA ===";
            foreach ($perKecamatan as $k) {
                $ctx[] = "- Kec. {$k->kecamatan} ({$k->kabupaten}): {$k->jumlah} mahasiswa";
            }
        }

        // ── 9. TOP 30 SEKOLAH ASAL (TARGET PROMOSI UTAMA) ───────────────
        $perSekolah = DB::table('sekolahs')
            ->select('nama_sekolah', 'kecamatan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('nama_sekolah')
            ->where('nama_sekolah', '!=', '')
            ->groupBy('nama_sekolah', 'kecamatan')
            ->orderByDesc('jumlah')
            ->limit(30)
            ->get();

        if ($perSekolah->isNotEmpty()) {
            $ctx[] = "\n=== TOP 30 SEKOLAH PENYUMBANG MAHASISWA (PRIORITAS PROMOSI) ===";
            foreach ($perSekolah as $s) {
                $lokasi = $s->kecamatan ? " — Kec. {$s->kecamatan}" : '';
                $ctx[] = "- {$s->nama_sekolah}{$lokasi}: {$s->jumlah} alumni masuk";
            }
        }

        // ── 10. SEKOLAH × PRODI ─────────────────────────────────────────
        $sekolahProdi = DB::table('sekolahs')
            ->join('mahasiswa', 'sekolahs.user_id', '=', 'mahasiswa.user_id')
            ->select('sekolahs.nama_sekolah', 'mahasiswa.prodi', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sekolahs.nama_sekolah')
            ->whereNotNull('mahasiswa.prodi')
            ->groupBy('sekolahs.nama_sekolah', 'mahasiswa.prodi')
            ->orderByDesc('jumlah')
            ->limit(40)
            ->get();

        if ($sekolahProdi->isNotEmpty()) {
            $ctx[] = "\n=== SEKOLAH × PROGRAM STUDI (sekolah mana ke prodi mana) ===";
            foreach ($sekolahProdi as $r) {
                $ctx[] = "- {$r->nama_sekolah} → {$r->prodi}: {$r->jumlah} mahasiswa";
            }
        }

        // ── 11. KABUPATEN × PRODI ───────────────────────────────────────
        $kabProdi = DB::table('alamat_mahasiswas')
            ->join('mahasiswa', 'alamat_mahasiswas.user_id', '=', 'mahasiswa.user_id')
            ->select('alamat_mahasiswas.kabupaten', 'mahasiswa.prodi', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('alamat_mahasiswas.kabupaten')
            ->whereNotNull('mahasiswa.prodi')
            ->groupBy('alamat_mahasiswas.kabupaten', 'mahasiswa.prodi')
            ->orderByDesc('jumlah')
            ->limit(40)
            ->get();

        if ($kabProdi->isNotEmpty()) {
            $ctx[] = "\n=== KABUPATEN × PROGRAM STUDI (minat prodi per daerah) ===";
            foreach ($kabProdi as $r) {
                $ctx[] = "- {$r->kabupaten} → {$r->prodi}: {$r->jumlah} mahasiswa";
            }
        }

        // ── 12. PROVINSI × PRODI ────────────────────────────────────────
        $provProdi = DB::table('alamat_mahasiswas')
            ->join('indonesia_provinces', 'alamat_mahasiswas.provinsi', '=', 'indonesia_provinces.code')
            ->join('mahasiswa', 'alamat_mahasiswas.user_id', '=', 'mahasiswa.user_id')
            ->select('indonesia_provinces.name as provinsi', 'mahasiswa.prodi', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('mahasiswa.prodi')
            ->groupBy('indonesia_provinces.name', 'mahasiswa.prodi')
            ->orderByDesc('jumlah')
            ->limit(30)
            ->get();

        if ($provProdi->isNotEmpty()) {
            $ctx[] = "\n=== PROVINSI × PROGRAM STUDI ===";
            foreach ($provProdi as $r) {
                $ctx[] = "- {$r->provinsi} → {$r->prodi}: {$r->jumlah} mahasiswa";
            }
        }

        // ── 13. BLANK SPOT provinsi tanpa mahasiswa ──────────────────────
        $allProvinsi = DB::table('indonesia_provinces')->pluck('name')->toArray();
        $adaProvinsi = $perProvinsi->pluck('provinsi')->toArray();
        $blankSpot   = array_values(array_diff($allProvinsi, $adaProvinsi));

        if (!empty($blankSpot)) {
            $ctx[] = "\n=== BLANK SPOT (Provinsi tanpa mahasiswa = peluang promosi) ===";
            $ctx[] = implode(', ', $blankSpot);
        }

        // ── 14. DATA KAPRODI (jika tabel ada) ───────────────────────────
        if (DB::getSchemaBuilder()->hasTable('kaprodis')) {
            $kaprodis = DB::table('kaprodis')->select('nama', 'program_studi')->get();
            if ($kaprodis->isNotEmpty()) {
                $ctx[] = "\n=== DATA KAPRODI ===";
                foreach ($kaprodis as $k) {
                    $ctx[] = "- {$k->program_studi}: Kaprodi {$k->nama}";
                }
            }
        }

        return implode("\n", $ctx);
    }

    private function hasColumn(string $table, string $column): bool
    {
        try {
            return DB::getSchemaBuilder()->hasColumn($table, $column);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Endpoint utama: kirim pesan ke SIGA
     */
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $apiKey = config('services.groq.key');

        $chatHistory   = session()->get('siga_chat_history', []);
        $chatHistory[] = ['role' => 'user', 'content' => $request->message];

        if (count($chatHistory) > 10) {
            $chatHistory = array_slice($chatHistory, -10);
        }

        try {
            $dbContext = $this->buildDatabaseContext();
        } catch (\Exception $e) {
            $dbContext = "⚠️ Gagal mengambil data database: " . $e->getMessage();
        }

        $systemPrompt = <<<PROMPT
Kamu adalah **SIGA** (Sistem Informasi Geografis Assistant), AI analis data mahasiswa yang powerful.

Kamu memiliki akses ke data real sistem SIG berikut ini:

{$dbContext}

=== KEMAMPUAN ANALISIS KAMU ===
1. 📊 **Statistik Mahasiswa** — distribusi per prodi, minat, jurusan, angkatan
2. 📍 **Analisis Geografis** — sebaran provinsi, kabupaten, kecamatan
3. 🏫 **Rekomendasi Promosi Sekolah** — sekolah mana yang paling banyak menyumbang mahasiswa = target utama promosi
4. 🗺️ **Blank Spot** — wilayah tanpa/sedikit mahasiswa = peluang ekspansi promosi
5. 📈 **Prediksi Tren** — estimasi jumlah mahasiswa angkatan mendatang berdasarkan historis
6. 🎯 **Rekomendasi Strategis** — saran konkret berbasis data untuk meningkatkan penerimaan mahasiswa

=== ATURAN WAJIB ===
- Jawab HANYA berdasarkan data yang ada di atas. JANGAN mengarang angka.
- Jika data tidak tersedia, katakan: "Data tersebut belum tersedia di sistem."
- Data sensitif (NIK, nomor HP, email, detail alamat, koordinat GPS) TIDAK ada di konteks ini dan tidak boleh diakses.
- Untuk prediksi, selalu sebut: "estimasi berdasarkan tren linear historis."
- Bahasa: Indonesia, profesional, ringkas, berbasis data.

=== FORMAT JAWABAN ===
- Gunakan emoji untuk keterbacaan: 📊 📍 🏫 📈 🎯 ✅ ⚠️
- Untuk rekomendasi promosi, gunakan label: 🔴 Prioritas Tinggi / 🟡 Sedang / 🟢 Ekspansi
- Sertakan angka spesifik dan persentase bila relevan
- Berikan insight yang actionable, bukan sekadar membacakan data mentah
PROMPT;

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $chatHistory
        );

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => 'llama-3.3-70b-versatile',
                    'messages'    => $messages,
                    'temperature' => 0.4,
                    'max_tokens'  => 1024,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Groq API error ' . $response->status() . ': ' . $response->body());
            }

            $data  = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'AI tidak memberikan respon.';

            $chatHistory[] = ['role' => 'assistant', 'content' => $reply];
            session()->put('siga_chat_history', $chatHistory);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json([
                'reply' => '⚠️ SIGA mengalami gangguan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset riwayat percakapan
     */
    public function resetChat()
    {
        session()->forget('siga_chat_history');
        return response()->json(['status' => 'ok', 'message' => 'Riwayat chat berhasil direset.']);
    }


//    public function briefing()
// {
//     $totalMahasiswa = DB::table('mahasiswa')->count();

//     $topProdi = DB::table('mahasiswa')
//         ->select('prodi', DB::raw('COUNT(*) as jumlah'))
//         ->whereNotNull('prodi')
//         ->groupBy('prodi')
//         ->orderByDesc('jumlah')
//         ->first();

//     $topKabupaten = DB::table('alamat_mahasiswas')
//         ->join(
//             'indonesia_cities',
//             'alamat_mahasiswas.kabupaten',
//             '=',
//             'indonesia_cities.code'
//         )
//         ->select(
//             'indonesia_cities.name',
//             DB::raw('COUNT(*) as jumlah')
//         )
//         ->groupBy('indonesia_cities.name')
//         ->orderByDesc('jumlah')
//         ->first();

//     $topSekolah = DB::table('sekolahs')
//         ->select(
//             'nama_sekolah',
//             DB::raw('COUNT(*) as jumlah')
//         )
//         ->whereNotNull('nama_sekolah')
//         ->groupBy('nama_sekolah')
//         ->orderByDesc('jumlah')
//         ->first();

//     return response()->json([
//         'total_mahasiswa' => $totalMahasiswa,
//         'top_prodi'       => $topProdi,
//         'top_kabupaten'   => $topKabupaten,
//         'top_sekolah'     => $topSekolah,
//     ]);
// }

public function insight()
{
    $totalMahasiswa = DB::table('mahasiswa')->count();

    $topProdi = DB::table('mahasiswa')
        ->select('prodi', DB::raw('COUNT(*) as jumlah'))
        ->groupBy('prodi')
        ->orderByDesc('jumlah')
        ->first();

    $topKabupaten = DB::table('alamat_mahasiswas')
        ->join(
            'indonesia_cities',
            'alamat_mahasiswas.kabupaten',
            '=',
            'indonesia_cities.code'
        )
        ->select(
            'indonesia_cities.name',
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('indonesia_cities.name')
        ->orderByDesc('jumlah')
        ->first();

    $topSekolah = DB::table('sekolahs')
        ->select(
            'nama_sekolah',
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('nama_sekolah')
        ->orderByDesc('jumlah')
        ->first();

    $persen = $totalMahasiswa > 0
        ? round(($topProdi->jumlah / $totalMahasiswa) * 100, 1)
        : 0;

    return response()->json([
        'insight' =>
            "🎯 {$topProdi->prodi} mendominasi {$persen}% mahasiswa.\n\n" .
            "📍 Hotspot mahasiswa berada di {$topKabupaten->name}.\n\n" .
            "🏫 Sekolah penyumbang terbesar adalah {$topSekolah->nama_sekolah}.\n\n" .
            "💡 SIGA menyarankan memperkuat promosi pada wilayah sekitar hotspot tersebut."
    ]);
}

// public function strategic()
// {
//     $topKabupaten = DB::table('alamat_mahasiswas')
//         ->join(
//             'indonesia_cities',
//             'alamat_mahasiswas.kabupaten',
//             '=',
//             'indonesia_cities.code'
//         )
//         ->select(
//             'indonesia_cities.name',
//             DB::raw('COUNT(*) as jumlah')
//         )
//         ->groupBy('indonesia_cities.name')
//         ->orderByDesc('jumlah')
//         ->limit(5)
//         ->get();

//     $topSekolah = DB::table('sekolahs')
//         ->select(
//             'nama_sekolah',
//             DB::raw('COUNT(*) as jumlah')
//         )
//         ->whereNotNull('nama_sekolah')
//         ->groupBy('nama_sekolah')
//         ->orderByDesc('jumlah')
//         ->limit(5)
//         ->get();

//     return response()->json([
//         'hotspot' => $topKabupaten,
//         'sekolah' => $topSekolah
//     ]);
// }


public function trend()
{
    try {

        // ==============================
        // 1. AMBIL DATA DARI DATABASE
        // ==============================
       $data = DB::table('mahasiswa')
    ->select(
        'prodi',
        DB::raw("CONCAT('20',LEFT(nim,2)) as angkatan")
    )
    ->get()
    ->map(function ($row) {
        return [
            'value' => 1,
            'meta' => [
                'prodi' => $row->prodi,
                'angkatan' => $row->angkatan
            ]
        ];
    })
    ->values();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'data mahasiswa kosong'
            ], 400);
        }

        // ==============================
        // 2. HIT FASTAPI (AI TREND ENGINE)
        // ==============================
        $response = Http::timeout(60)
            ->post('http://127.0.0.1:8001/trend', [
                'data' => $data
            ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => 'error',
                'message' => 'AI engine error',
                'raw' => $response->body()
            ], 500);
        }

        $result = $response->json();

        // ==============================
        // 3. BUAT PROMPT GROQ
        // ==============================
        $prompt = "
Kamu adalah analis PMB kampus.

Berikut hasil analisis trend mahasiswa:

" . json_encode($result, JSON_PRETTY_PRINT) . "

Analisis dengan bahasa manusia sederhana.

Jelaskan:
1. Prodi yang naik
2. Prodi yang turun
3. Prodi yang stabil
4. Penyebab kemungkinan
5. Prediksi tahun depan
6. Strategi promosi fakultas teknik

Gunakan format:

📈 Tren Utama

🔍 Analisis Penyebab

🔮 Prediksi

🎯 Rekomendasi
";

        // ==============================
        // 4. HIT GROQ (LLM INSIGHT)
        // ==============================
        $groq = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type' => 'application/json'
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.4,
                'max_tokens' => 1000
            ]);

        if (!$groq->successful()) {
            return response()->json([
                'status' => 'error',
                'message' => 'groq error',
                'raw' => $groq->body()
            ], 500);
        }

        $reply = $groq->json()['choices'][0]['message']['content']
            ?? 'Analisis tidak tersedia';

        // ==============================
        // 5. RESPONSE FINAL
        // ==============================
        return response()->json([
            'status' => 'success',
            'insight' => $reply,
            'raw_trend' => $result
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
public function anomaly()
{
    try {

        $stats = DB::table('alamat_mahasiswas')
            ->join(
                'mahasiswa',
                'alamat_mahasiswas.user_id',
                '=',
                'mahasiswa.user_id'
            )
            ->leftJoin(
                'sekolahs',
                'alamat_mahasiswas.user_id',
                '=',
                'sekolahs.user_id'
            )
            ->select(
                'alamat_mahasiswas.provinsi',
                'alamat_mahasiswas.kabupaten',
                'mahasiswa.prodi',
                'mahasiswa.minat',
                'mahasiswa.jurusan',
                'sekolahs.nama_sekolah',
                DB::raw("
                    CONCAT(
                        '20',
                        LEFT(mahasiswa.nim,2)
                    ) as angkatan
                ")
            )
            ->get()
            ->map(function ($row) {

                return [
                    'label' => $row->kabupaten,
                    'value' => 1,

                    'meta' => [

                        'provinsi' => $row->provinsi,
                        'kabupaten' => $row->kabupaten,

                        'prodi' => $row->prodi,
                        'minat' => $row->minat,
                        'jurusan' => $row->jurusan,

                        'nama_sekolah' => $row->nama_sekolah,

                        'angkatan' => $row->angkatan
                    ]
                ];
            })
            ->values();

        $response = Http::timeout(120)
            ->post(
                'http://127.0.0.1:8001/anomaly',
                [
                    'data' => $stats
                ]
            );

        if (!$response->successful()) {

            return response()->json([
                'status' => 'error',
                'http_status' => $response->status(),
                'raw' => $response->body()
            ]);
        }

        $result = $response->json();

        if (
            empty($result) ||
            empty($result['anomalies'])
        ) {

            return response()->json([
                'status' => 'normal',
                'insight' =>
                    'Belum ditemukan perubahan signifikan antar angkatan.',
                'raw' => $result
            ]);
        }

        $prompt = "

Kamu adalah analis PMB kampus.

Data berikut menunjukkan perubahan jumlah mahasiswa antar angkatan berdasarkan wilayah.

" . json_encode(
            $result['anomalies'],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ) . "

Tugas:

1. Cari wilayah yang mengalami kenaikan terbesar.
2. Cari wilayah yang mengalami penurunan terbesar.
3. Jelaskan kemungkinan penyebab berdasarkan:
   - prodi dominan
   - minat dominan
   - jurusan dominan
   - sekolah dominan
4. Jelaskan dampaknya bagi fakultas teknik.
5. Berikan rekomendasi strategi promosi.

Gunakan format:

📈 Analisis

🔍 Kemungkinan Penyebab

⚠️ Dampak

🎯 Rekomendasi

Gunakan bahasa manusia yang mudah dipahami pimpinan kampus.

";

        $groq = Http::timeout(120)
            ->withHeaders([
                'Authorization' =>
                    'Bearer ' .
                    config('services.groq.key'),

                'Content-Type' => 'application/json'
            ])
            ->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' => 'llama-3.3-70b-versatile',

                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],

                    'temperature' => 0.4,
                    'max_tokens' => 1000
                ]
            );

        $reply =
            $groq->json()['choices'][0]['message']['content']
            ?? 'Analisis tidak tersedia';

        return response()->json([

            'status' => 'success',

            'insight' => $reply,

            'raw_anomaly' => $result
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

   public function distribution(Request $request)
{
    try {

$dataPerKecamatan = DB::table('alamat_mahasiswas as a')
    ->leftJoin(
        'indonesia_districts as d',
        'a.kecamatan',
        '=',
        'd.code'
    )
    ->leftJoin(
        'indonesia_cities as c',
        'd.city_code',
        '=',
        'c.code'
    )
    ->selectRaw('
        COALESCE(d.name, a.kecamatan) as district,
        COALESCE(c.name, "-") as city,
        COUNT(*) as count
    ')
    ->whereNotNull('a.kecamatan')
    ->groupByRaw('
        COALESCE(d.name, a.kecamatan),
        COALESCE(c.name, "-")
    ')
    ->get();

        $response = Http::timeout(60)
            ->post(
                'http://127.0.0.1:8001/distribution',
                [
                    'data' => $dataPerKecamatan,
                    'zoom_level' => 10
                ]
            );

        if (!$response->successful()) {

            return response()->json([
                'status' => 'error',
                'raw' => $response->body()
            ]);
        }

        $result = $response->json();

        $prompt = "

Kamu adalah analis Sistem Informasi Geografis (SIG) kampus.

Berikut hasil analisis distribusi mahasiswa:

" . json_encode($result, JSON_PRETTY_PRINT) . "

Analisis dengan bahasa manusia.

Jelaskan:

1. Kecamatan dengan mahasiswa terbanyak.
2. Tingkat kepadatan persebaran mahasiswa.
3. Apakah persebaran terkonsentrasi atau merata.
4. Karakteristik pola persebaran mahasiswa.
5. Ringkasan kondisi sebaran saat ini.

Gunakan format:

📍 Sebaran Utama

📊 Kepadatan

🧭 Pola Persebaran

📌 Kesimpulan

";

        $groq = Http::timeout(60)
            ->withHeaders([
                'Authorization' =>
                    'Bearer ' .
                    config('services.groq.key')
            ])
            ->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' =>
                        'llama-3.3-70b-versatile',

                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],

                    'temperature' => 0.4,
                    'max_tokens' => 1000
                ]
            );

        $reply =
            $groq->json()['choices'][0]['message']['content']
            ?? 'Analisis distribusi tidak tersedia';

        return response()->json([
            'status' => 'success',
            'insight' => $reply,
            'raw_distribution' => $result
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
    
}