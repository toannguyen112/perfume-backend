<?php

namespace Jamstackvietnam\Support\Traits;

use Jamstackvietnam\Support\Models\File;
use \Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Log;

trait HasRichText
{
    public static function bootHasRichText()
    {
        static::saving(function ($model) {
            if (request()->route() === null) return;
            $model->store_media_from_url();
        });
    }

    private function store_media_from_url()
    {
        $regex = '/src\s*=\s*"(.+?)"/m';

        foreach ($this->richtext as $field) {
            $content = $this->{$field};

            if (empty($content)) {
                continue;
            }

            preg_match_all($regex, $content, $matches, PREG_SET_ORDER, 0);

            if (empty($matches)) {
                continue;
            }

            foreach ($matches as $match) {
                $url = $match[1];

                if (strstr($url, 'storage/media/')) {
                    continue;
                }

                $originUrl = $url;

                try {
                    $file = file_get_contents($url);
                    $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($file);
                    $extension = explode('/', $mime)[1] ?? 'png';

                    if (!in_array($extension, ['gif', 'png', 'jpeg', 'jpg', 'tiff'])) {
                        Log::info('-------------------');
                        Log::info($originUrl);
                        Log::info('-------------------');
                        continue;
                    }

                    $fileName = md5($url . time());
                    $path = "/storage/media/$fileName.$extension";
                    file_put_contents(public_path($path), $file);

                    File::create([
                        'user_id'   => 1,
                        'disk'      => config('filesystems.default'),
                        'filename'  => FacadesFile::basename(public_path($path)),
                        'path'      => str_replace('/storage/', '', $path),
                        'extension' => FacadesFile::extension(public_path($path)),
                        'mime'      => $mime,
                        'size'      => FacadesFile::size(public_path($path)),
                    ]);

                    $content = str_replace($originUrl, url($path), $content);

                    $this->{$field} = $content;
                } catch (\Exception $exception) {
                    Log::error('---------store_media_from_url----------');
                    Log::error($exception->getMessage());
                    Log::error($url);
                    Log::error('-------------------');
                }
            }
        }
    }
}
