<?php

namespace App\Repositories;

use App\Interfaces\UtilRepositoryInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UtilRepository implements UtilRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function migrate()
    {
        Artisan::call('migrate');

        return '✅ Migraciones ejecutadas correctamente.';
    }

    public function cleanAll()
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('event:clear');
        Artisan::call('optimize:clear');

        return '✅ Migraciones ejecutadas correctamente.';
    }

    public function refreshCaches()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return '🧹 Limpieza completada: configuración, rutas, vistas, cache y eventos.';
    }

    public function storageCopy()
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = File::allFiles($source);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $destPath = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if (!File::exists($destPath)) {
                File::ensureDirectoryExists(dirname($destPath));
                File::copy($file->getPathname(), $destPath);
            }
        }

        return 'Storage copied with only new files.';
    }
}
