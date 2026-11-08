@php
    // Nama singkat di badge
    $short = ['Bacterialblight' => 'Bacterialblight', 'Blast' => 'Blast', 'Brownspot' => 'Brown Spot', 'Tungro' => 'Tungro'];

    // Ciri ringkas (satu baris tiap ciri). Penyakit yang tidak tercantum memakai teks aslinya dari config.
    $ciri = [
        'Bacterialblight' => ['Bercak kehijauan basah di ujung atau tepi daun', 'Bercak melebar, kuning lalu putih keabuan'],
        'Blast'           => ['Bercak belah ketupat, pusat abu-abu, tepi coklat', 'Bercak menyatu hingga daun mengering'],
        'Tungro'          => ['Daun kuning hingga oranye dari ujung daun', 'Tanaman kerdil dan anakan berkurang'],
    ];

    // Gambar penyakit: public/images/penyakit/<Kelas>.jpg atau public/images/<Kelas>.jpg (jpg, jpeg, png, webp)
    $diseaseImg = function ($key) {
        foreach (['images/penyakit/', 'images/'] as $dir) {
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                foreach ([$key, strtolower($key)] as $name) {
                    if (is_file(public_path($dir.$name.'.'.$ext))) {
                        return asset($dir.$name.'.'.$ext);
                    }
                }
            }
        }

        return null;
    };
@endphp
<section class="jd" id="jenis-penyakit" aria-label="Jenis penyakit">
    <div class="container jd-in">
        <header class="jd-head">
            <span class="jd-eyebrow">
                <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg></i>
                Jenis penyakit
            </span>
            <div class="jd-titlewrap">
                <span class="jd-leaf jd-leaf-l" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" fill-opacity=".2"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" fill="none"/></svg></span>
                <h2 class="jd-title">Penyakit yang dapat <em>dideteksi</em></h2>
                <span class="jd-leaf jd-leaf-r" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" fill-opacity=".2"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" fill="none"/></svg></span>
            </div>
        </header>

        <div class="jd-grid">
            @foreach ($diseases as $key => $d)
                @php
                    $img    = $diseaseImg($key);
                    $gejala = $ciri[$key] ?? array_slice($d['symptoms'], 0, 2);
                @endphp
                <article class="jd-card acc-{{ $d['tone'] }}" style="--i: {{ $loop->index }}">
                    <div class="jd-img">
                        @if ($img)
                            <img src="{{ $img }}" alt="Contoh {{ $short[$key] ?? $d['name'] }}" loading="lazy">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                        @endif
                        <span class="jd-risk risk-{{ \Illuminate\Support\Str::slug($d['risk']) }}">Risiko {{ $d['risk'] }}</span>
                    </div>
                    <div class="jd-body">
                        <h3 class="jd-name">{{ $short[$key] ?? $d['name'] }}</h3>
                        <p class="jd-latin">{{ $d['latin'] }}</p>
                        <p class="jd-sum">{{ $d['summary'] }}</p>
                        <ul class="jd-list">
                            @foreach ($gejala as $i => $g)
                                <li title="{{ $d['symptoms'][$i] ?? $g }}">{{ $g }}</li>
                            @endforeach
                        </ul>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>