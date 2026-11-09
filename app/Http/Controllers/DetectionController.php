<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Services\PadiClassifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class DetectionController extends Controller
{
    public function home()
    {
        return view('home', [
            'diseases' => config('padi.diseases'),
            'total'    => Analysis::count(),
            'recent'   => Analysis::latest()->take(4)->get(),
        ]);
    }

    public function create()
    {
        return view('detect');
    }

    public function store(Request $request, PadiClassifier $classifier)
    {
        $request->validate([
            'foto' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'foto.required' => 'Silakan pilih foto daun padi terlebih dahulu.',
            'foto.file'     => 'Berkas yang diunggah tidak valid.',
            'foto.image'    => 'Berkas harus berupa gambar.',
            'foto.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max'      => 'Ukuran foto maksimal 8 MB.',
            'foto.uploaded' => 'Unggahan gagal. Periksa ukuran foto (maks. 8 MB) lalu coba lagi.',
        ]);

        $file = $request->file('foto');
        $dir  = public_path('uploads/analisis');

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $original = $file->getClientOriginalName();
        $name     = Str::uuid()->toString().'.'.($file->guessExtension() ?: 'jpg');
        $file->move($dir, $name);
        $fullPath = $dir.DIRECTORY_SEPARATOR.$name;

        try {
            $result = $classifier->predict($fullPath);
        } catch (Throwable $e) {
            @unlink($fullPath);
            report($e);

            return back()->withErrors(['foto' => 'Analisis gagal: '.$e->getMessage()]);
        }

        $status = $result['status'] ?? 'ok';

        if ($status === 'not_leaf') {
            @unlink($fullPath);

            return back()->with('unknown', $result['reason'] ?? 'color');
        }

        if ($status === 'no_symptoms') {
            // shortcut: padi sehat disimpan dengan label 'Healthy', confidence 0, dan isi probabilities diganti ukuran warna foto (green/lesion, 0..1)
            $result = [
                'label'         => 'Healthy',
                'confidence'    => 0,
                'probabilities' => $result['metrics'] ?? [],
            ];
        }

        $analysis = Analysis::create([
            'image'         => $name,
            'original_name' => null,
            'label'         => $result['label'],
            'confidence'    => $result['confidence'],
            'probabilities' => $result['probabilities'],
        ]);

        return redirect()->route('hasil', $analysis);
    }

    public function show(Analysis $analysis)
    {
        if ($analysis->label === 'Healthy') {
            return view('healthy', ['analysis' => $analysis]);
        }

        return view('result', [
            'analysis'      => $analysis,
            'probabilities' => collect($analysis->probabilities)->sortDesc(),
        ]);
    }

    public function history(Request $request)
    {
        $diseases = config('padi.diseases');
        $filter   = $request->query('penyakit');
        $filter   = ((string) $filter === 'Healthy' || array_key_exists((string) $filter, $diseases)) ? $filter : null;

        // shortcut: semua data dimuat sekali dan dipaginasi 4 per halaman di browser (history.js), bila riwayat >~300 pindahkan pencarian dan halaman ke server
        $analyses = Analysis::query()
            ->when($filter, fn ($q) => $q->where('label', $filter))
            ->latest()
            ->get();

        $counts = Analysis::select('label', DB::raw('count(*) as total'))
            ->groupBy('label')
            ->pluck('total', 'label');

        return view('history', compact('analyses', 'diseases', 'filter', 'counts'));
    }

    public function destroy(Analysis $analysis)
    {
        if (is_file($analysis->image_path)) {
            @unlink($analysis->image_path);
        }
        $analysis->delete();

        return redirect()->route('riwayat')->with('status', 'Riwayat analisis berhasil dihapus.');
    }
}
