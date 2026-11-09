<?php

namespace App\Console\Commands;

use App\Services\ImageStore;
use Illuminate\Console\Command;
use Throwable;

class MigrasiFotoKeCloudinary extends Command
{
    protected $signature = 'padi:migrasi-foto';

    protected $description = 'Unggah foto di public/uploads/analisis ke Cloudinary';

    public function handle(ImageStore $images): int
    {
        if (! $images->usesCloudinary()) {
            $this->error('Isi CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, dan CLOUDINARY_API_SECRET di .env terlebih dahulu.');

            return self::FAILURE;
        }

        $files = glob(public_path('uploads/analisis/*')) ?: [];
        $gagal = 0;

        $this->withProgressBar($files, function (string $path) use ($images, &$gagal) {
            try {
                $images->put($path, basename($path));
            } catch (Throwable $e) {
                $gagal++;
                $this->newLine();
                $this->warn(basename($path).': '.$e->getMessage());
            }
        });

        $this->newLine(2);
        $this->info(count($files) - $gagal.' foto berhasil diunggah, '.$gagal.' gagal.');

        return $gagal ? self::FAILURE : self::SUCCESS;
    }
}