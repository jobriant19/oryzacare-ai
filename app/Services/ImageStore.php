<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Penyimpanan foto analisis.
 * Cloudinary dipakai bila CLOUDINARY_* terisi (production/Vercel),
 * selain itu disimpan di public/uploads/analisis (lokal/Laragon).
 */
class ImageStore
{
    private const LOCAL_DIR = 'uploads/analisis';

    public function usesCloudinary(): bool
    {
        return filled(config('padi.cloudinary.cloud'))
            && filled(config('padi.cloudinary.key'))
            && filled(config('padi.cloudinary.secret'));
    }

    public function put(string $sourcePath, string $name): void
    {
        $this->usesCloudinary()
            ? $this->putCloudinary($sourcePath, $name)
            : $this->putLocal($sourcePath, $name);
    }

    public function url(string $name): string
    {
        if ($this->usesCloudinary()) {
            return sprintf(
                'https://res.cloudinary.com/%s/image/upload/%s/%s',
                config('padi.cloudinary.cloud'),
                config('padi.cloudinary.folder'),
                $name
            );
        }

        return asset(self::LOCAL_DIR.'/'.$name);
    }

    public function delete(string $name): void
    {
        if ($this->usesCloudinary()) {
            Http::timeout(15)->post(
                $this->endpoint('destroy'),
                $this->sign(['public_id' => $this->publicId($name)])
            );

            return;
        }

        $path = public_path(self::LOCAL_DIR.'/'.$name);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function putLocal(string $sourcePath, string $name): void
    {
        $dir = public_path(self::LOCAL_DIR);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        copy($sourcePath, $dir.DIRECTORY_SEPARATOR.$name);
    }

    private function putCloudinary(string $sourcePath, string $name): void
    {
        $response = Http::timeout(30)
            ->attach('file', file_get_contents($sourcePath), $name)
            ->post($this->endpoint('upload'), $this->sign(['public_id' => $this->publicId($name)]));

        if (! $response->successful()) {
            throw new RuntimeException('Cloudinary: '.($response->json('error.message') ?? 'HTTP '.$response->status()));
        }
    }

    private function publicId(string $name): string
    {
        return config('padi.cloudinary.folder').'/'.pathinfo($name, PATHINFO_FILENAME);
    }

    private function endpoint(string $action): string
    {
        return sprintf('https://api.cloudinary.com/v1_1/%s/image/%s', config('padi.cloudinary.cloud'), $action);
    }

    /** Tambahkan timestamp, signature, dan api_key sesuai aturan signed upload Cloudinary. */
    private function sign(array $params): array
    {
        $params['timestamp'] = time();
        ksort($params);

        $toSign = implode('&', array_map(fn ($k, $v) => "$k=$v", array_keys($params), $params));

        $params['signature'] = sha1($toSign.config('padi.cloudinary.secret'));
        $params['api_key']   = config('padi.cloudinary.key');

        return $params;
    }
}