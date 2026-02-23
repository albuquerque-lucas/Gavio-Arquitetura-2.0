<?php

namespace App\Http\Controllers;

use App\Models\SiteAsset;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AdminSiteAssetController extends Controller
{
    public function edit()
    {
        return view('admin-site-assets.edit');
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'home_background' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:30720',
                'brand_logo_icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
                'brand_logo_written' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
                'project_cover_fallback' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            $uploads = [
                'home_background' => ['directory' => 'site-assets/backgrounds', 'legacy_key' => null],
                'brand_logo_icon' => ['directory' => 'site-assets/logos', 'legacy_key' => 'brand_logo_primary'],
                'brand_logo_written' => ['directory' => 'site-assets/logos', 'legacy_key' => 'brand_logo_secondary'],
                'project_cover_fallback' => ['directory' => 'site-assets/projects', 'legacy_key' => null],
            ];

            $updatedCount = 0;

            foreach ($uploads as $key => $config) {
                if (! $request->hasFile($key)) {
                    continue;
                }

                $file = $request->file($key);
                $legacyKey = $config['legacy_key'];
                $existing = $this->findExistingAsset($key, $legacyKey);

                if ($existing && $existing->path) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $existing->path));
                    if ($existing->key !== $key) {
                        $existing->delete();
                    }
                }

                $storedAsset = $this->storeOptimizedAsset($file, $key, $config['directory']);

                SiteAsset::updateOrCreate(
                    ['key' => $key],
                    [
                        'path' => '/storage/'.$storedAsset['path'],
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $storedAsset['mime_type'],
                        'size' => $storedAsset['size'],
                    ]
                );

                $updatedCount++;
            }

            if ($updatedCount === 0) {
                return redirect()->route('admin.appearance.edit')->with('success', 'Nenhum novo arquivo foi enviado.');
            }

            return redirect()->route('admin.appearance.edit')->with('success', 'Assets do site atualizados com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar assets: '.$e->getMessage());
        }
    }

    private function findExistingAsset(string $key, ?string $legacyKey): ?SiteAsset
    {
        $preferred = SiteAsset::where('key', $key)->first();
        if ($preferred) {
            return $preferred;
        }

        if ($legacyKey) {
            return SiteAsset::where('key', $legacyKey)->first();
        }

        return null;
    }

    private function storeOptimizedAsset(UploadedFile $file, string $key, string $directory): array
    {
        $mime = strtolower((string) $file->getClientMimeType());

        if (str_contains($mime, 'svg')) {
            $filename = $key.'_'.time().'.svg';
            $path = $file->storeAs($directory, $filename, 'public');

            return [
                'path' => $path,
                'mime_type' => $mime ?: 'image/svg+xml',
                'size' => $file->getSize(),
            ];
        }

        $maxWidth = $key === 'home_background' ? 2560 : 1200;
        $quality = $key === 'home_background' ? 76 : 82;

        $manager = new ImageManager(new Driver);
        $image = $manager->read($file->getRealPath());

        if (method_exists($image, 'scaleDown') && $image->width() > $maxWidth) {
            $image = $image->scaleDown(width: $maxWidth);
        }

        $encoded = $image->toWebp($quality);
        $filename = $key.'_'.time().'.webp';
        $path = $directory.'/'.$filename;
        $binary = (string) $encoded;

        Storage::disk('public')->put($path, $binary);

        return [
            'path' => $path,
            'mime_type' => 'image/webp',
            'size' => strlen($binary),
        ];
    }
}
