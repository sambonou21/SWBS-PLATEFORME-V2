<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    public function uploadServiceImage(UploadedFile $file): string
    {
        return $this->process($file, 'services');
    }

    public function uploadPortfolioImage(UploadedFile $file): string
    {
        return $this->process($file, 'portfolio');
    }

    public function uploadProductImage(UploadedFile $file): string
    {
        return $this->process($file, 'products');
    }

    protected function process(UploadedFile $file, string $folder): string
    {
        $this->validateMime($file);

        $uuid = (string) Str::uuid();
        $path = "uploads/{$folder}/{$uuid}.webp";

        $image = Image::read($file->getPathname());
        $image->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $webp = $image->toWebp(80);

        $fullPath = public_path($path);
        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, $webp);

        return '/'.$path;
    }

    protected function validateMime(UploadedFile $file): void
    {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (! in_array($file->getMimeType(), $allowed, true)) {
            abort(422, 'Format d’image non supporté.');
        }
    }
}