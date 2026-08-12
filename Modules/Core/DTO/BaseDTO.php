<?php

namespace Modules\Core\DTO;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Traits\Dumpable;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as ImageDriver;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;

class BaseDTO
{
    use Dumpable;
    public function toArray(): array
    {
        $array = (array) $this;
        if (array_key_exists('id', $array)) {
            unset($array['id']);
        }

        return $array;
    }

    public function filterNull(array $skip = []): self
    {
        $attributesArray = (array) $this;

        foreach ($attributesArray as $attribute => $value) {
            if (is_null($value) && (!in_array($attribute, $skip))) {
                unset($this->{$attribute});
            }
        }

        return $this;
    }

    public function filter(array $attributes = [], array $values = [], array $skip = []): self
    {
        $attributesArray = (array) $this;

        $valuesIsSet = count($values);
        $attributesIsSet = count($attributes);

        if ($attributesIsSet || $valuesIsSet) {

            foreach ($attributesArray as $attribute => $value) {

                if ((in_array($value, $values) || in_array($attribute, $attributes) && (!in_array($attribute, $skip)))) {
                    unset($this->{$attribute});
                }
            }
        }

        return $this;
    }

    public static function handleFileStoring(UploadedFile|string|null $file, string $path, ?string $name = null, string $disk = 'public'): ?string
    {
        if (is_null($file)) {
            return null;
        }

        if (is_string($file)) {
            return $file;
        }

        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            try {
                $manager = new ImageManager(new ImageDriver());
                $image = $manager->read($file->getRealPath());

                $image = $image->toWebp();

                if (is_null($name)) {
                    $name = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                } else {
                    $name = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                }

                $tempPath = sys_get_temp_dir() . '/' . $name;
                $image->save($tempPath);

                $fullPath = Storage::disk($disk)->putFileAs($path, new \Illuminate\Http\File($tempPath), $name);

                unlink($tempPath);
            } catch (\Throwable $e) {
                $fullPath = $file->storePublicly($path, $disk);
            }
        } elseif (str_starts_with($mimeType, 'video/')) {
            try {
                $ffmpeg = FFMpeg::create();
                $video = $ffmpeg->open($file->getRealPath());

                if (is_null($name)) {
                    $name = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webm';
                } else {
                    $name = pathinfo($name, PATHINFO_FILENAME) . '.webm';
                }

                $tempPath = sys_get_temp_dir() . '/' . $name;

                $format = new \FFMpeg\Format\Video\WebM();
                $video->save($format, $tempPath);

                $fullPath = Storage::disk($disk)->putFileAs($path, new \Illuminate\Http\File($tempPath), $name);

                unlink($tempPath);
            } catch (\Throwable $e) {
                $fullPath = $file->storePublicly($path, $disk);
            }
        } else {
            $fullPath = $file->storePublicly($path, $disk);
        }

        return 'storage/' . $fullPath;
    }

    public static function handleMultipleFilesStoring(?array $files, string $path, ?array $names = null, string $disk = 'public'): array
    {
        if (is_null($files)) {
            return [];
        }

        return array_map(function ($file, $index) use ($path, $names, $disk) {
            $name = $names[$index] ?? null;
            return self::handleFileStoring($file, $path, $name, $disk);
        }, $files, array_keys($files));
    }

    public static function prepareRequestArray(?array $data): ?array
    {
        if (!is_array($data)) {
            return null;
        }

        if (empty($data)) {
            return [];
        }

        if (in_array(-1, $data, true)) {
            return [];
        }

        return $data;
    }

    public static function prepareDateTime($date)
    {
        if (is_null($date)) {
            return null;
        }
        return \Carbon\Carbon::parse($date);
    }
}
