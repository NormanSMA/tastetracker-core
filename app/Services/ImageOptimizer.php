<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    /**
     * Optimiza y convierte una imagen a WebP.
     *
     * @param  UploadedFile  $file  El archivo subido desde el formulario.
     * @param  string  $directory  El directorio de destino dentro del disco.
     * @param  int|null  $width  Ancho máximo opcional.
     * @param  string  $disk  Disco de almacenamiento (default: public).
     * @return string|null La ruta relativa del archivo guardado.
     */
    public static function optimizeToWebp(UploadedFile $file, string $directory, ?int $width = null, string $disk = 'public'): ?string
    {
        $manager = new ImageManager(new Driver);

        // Cargar la imagen
        $image = $manager->read($file->getRealPath());

        // Redimensionar si se especifica un ancho
        if ($width) {
            $image->scale(width: $width);
        }

        // Generar nombre de archivo único
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'_'.uniqid().'.webp';
        $path = $directory.'/'.$filename;

        // Convertir a WebP con calidad 80 y guardar en el disco
        $webpContent = $image->toWebp(80);

        Storage::disk($disk)->put($path, (string) $webpContent);

        return $path;
    }
}
