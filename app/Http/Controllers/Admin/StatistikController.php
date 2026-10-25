<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    // Berapa banyak item yang ditampilkan di masing-masing chart baru
    const LIMIT_SEKOLAH   = 10;
    const LIMIT_PROVINSI  = 5;
    const LIMIT_KABUPATEN = 10;

    public function index()
    {
        $totalMahasiswa = Mahasiswa::count();

        $totalProdi = Mahasiswa::distinct('prodi')->count('prodi');

        $prodiStats = Mahasiswa::select(
                'prodi',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('prodi')
            ->orderByDesc('total')
            ->get();

        $angkatanStats = DB::table('mahasiswa')
            ->selectRaw("
                LEFT(nim,2) as angkatan_kode,
                COUNT(*) as total
            ")
            ->groupBy('angkatan_kode')
            ->orderBy('angkatan_kode')
            ->get()
            ->map(function ($item) {
                $item->angkatan = '20' . $item->angkatan_kode;
                return $item;
            });

        // =====================================================
        // BARU: Top Sekolah (berdasarkan sekolahs.nama_sekolah)
        // =====================================================
        $sekolahStats = DB::table('mahasiswa')
            ->join('sekolahs', 'mahasiswa.user_id', '=', 'sekolahs.user_id')
            ->whereNotNull('sekolahs.nama_sekolah')
            ->select('sekolahs.nama_sekolah', DB::raw('COUNT(*) as total'))
            ->groupBy('sekolahs.nama_sekolah')
            ->orderByDesc('total')
            ->limit(self::LIMIT_SEKOLAH)
            ->get();

        // =====================================================
        // BARU: Top Provinsi (berdasarkan domisili di alamat_mahasiswas)
        // =====================================================
        $provinsiStats = DB::table('mahasiswa')
            ->join('alamat_mahasiswas', 'mahasiswa.user_id', '=', 'alamat_mahasiswas.user_id')
            ->join('indonesia_provinces', 'alamat_mahasiswas.provinsi', '=', 'indonesia_provinces.code')
            ->select(
                'indonesia_provinces.code as kode_provinsi',
                'indonesia_provinces.name as provinsi',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('indonesia_provinces.code', 'indonesia_provinces.name')
            ->orderByDesc('total')
            ->limit(self::LIMIT_PROVINSI)
            ->get();

        // =====================================================
        // BARU: Top Kabupaten (berdasarkan domisili di alamat_mahasiswas)
        // =====================================================
        $kabupatenStats = DB::table('mahasiswa')
            ->join('alamat_mahasiswas', 'mahasiswa.user_id', '=', 'alamat_mahasiswas.user_id')
            ->join('indonesia_cities', 'alamat_mahasiswas.kabupaten', '=', 'indonesia_cities.code')
            ->select(
                'indonesia_cities.code as kode_kabupaten',
                'indonesia_cities.name as kabupaten',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('indonesia_cities.code', 'indonesia_cities.name')
            ->orderByDesc('total')
            ->limit(self::LIMIT_KABUPATEN)
            ->get();

        return view('admin.statistik.index', compact(
            'totalMahasiswa',
            'totalProdi',
            'prodiStats',
            'angkatanStats',
            'sekolahStats',
            'provinsiStats',
            'kabupatenStats'
        ));
    }

    /**
     * BARU: Detail breakdown per prodi untuk 1 sekolah (dipanggil via AJAX saat bar chart diklik)
     * GET /admin/statistik/detail-sekolah?nama=MAN+2+PONOROGO
     */
    public function detailSekolah(Request $request)
    {
        $nama = $request->query('nama');

        $items = DB::table('mahasiswa')
            ->join('sekolahs', 'mahasiswa.user_id', '=', 'sekolahs.user_id')
            ->where('sekolahs.nama_sekolah', $nama)
            ->select('mahasiswa.prodi', DB::raw('COUNT(*) as total'))
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'label' => $nama,
            'total' => $items->sum('total'),
            'items' => $items,
        ]);
    }

    /**
     * BARU: Detail breakdown per prodi untuk 1 provinsi (domisili)
     * GET /admin/statistik/detail-provinsi?kode=35
     */
    public function detailProvinsi(Request $request)
    {
        $kode = $request->query('kode');

        $provinsi = DB::table('indonesia_provinces')->where('code', $kode)->value('name');

        $items = DB::table('mahasiswa')
            ->join('alamat_mahasiswas', 'mahasiswa.user_id', '=', 'alamat_mahasiswas.user_id')
            ->where('alamat_mahasiswas.provinsi', $kode)
            ->select('mahasiswa.prodi', DB::raw('COUNT(*) as total'))
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'label' => $provinsi ?? $kode,
            'total' => $items->sum('total'),
            'items' => $items,
        ]);
    }

    /**
     * BARU: Detail breakdown per prodi untuk 1 kabupaten (domisili)
     * GET /admin/statistik/detail-kabupaten?kode=3520
     */
    public function detailKabupaten(Request $request)
    {
        $kode = $request->query('kode');

        $kabupaten = DB::table('indonesia_cities')->where('code', $kode)->value('name');

        $items = DB::table('mahasiswa')
            ->join('alamat_mahasiswas', 'mahasiswa.user_id', '=', 'alamat_mahasiswas.user_id')
            ->where('alamat_mahasiswas.kabupaten', $kode)
            ->select('mahasiswa.prodi', DB::raw('COUNT(*) as total'))
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'label' => $kabupaten ?? $kode,
            'total' => $items->sum('total'),
            'items' => $items,
        ]);
    }

    public function export()
    {
        if (auth()->user()->role !== 'kaprodi') {
            abort(403);
        }
    }
}