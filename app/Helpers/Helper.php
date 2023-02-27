<?php

use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('base_url')) {
    /**
     * Generate a HTTPS url for the application.
     *
     * @param string $path
     * @param mixed $parameters
     *
     * @return string
     */
    function base_url(string $path = '', $parameters = []): string
    {
        if (!empty($parameters)) {
            return env('APP_URL') . '/' . $path . '?' . http_build_query($parameters);
        } else {
            return env('APP_URL') . '/' . $path;
        }
    }
}

if (!function_exists('static_url')) {
    function static_url($path, $parameters = [])
    {
        if (!$path || contains($path, 'http')) return $path;
        if (!empty($parameters)) {
            return env('STATIC_URL') . '/' . $path . '?' . http_build_query($parameters);
        } else {
            return env('STATIC_URL') . '/' . $path;
        }
    }
}

if (!function_exists('price_format')) {
    function price_format($price)
    {
        if (!$price) {
            return '0 đ';
        }
        return number_format((float)$price, 0, '.', '.') . ' đ';
    }
}

if (!function_exists('datetime_format')) {
    function datetime_format($date, $format = 'd/m/Y H:i')
    {
        if (empty($date)) return '';

        return ucfirst(Illuminate\Support\Carbon::create($date)
            ->setTimezone('Asia/Ho_Chi_Minh')
            ->translatedFormat($format));
    }
}

if (!function_exists('contains')) {
    function contains($string, $needle): bool
    {
        return strpos($string, $needle) !== false;
    }
}

if (!function_exists('generate_code')) {
    function generate_code($length = 15, $characters = 'all'): string
    {
        $characters = [
            'all' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'number' => '0123456789',
            'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
        ][$characters];

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('transform_translation')) {
    function transform_translation($translations): array
    {
        $localeTranslations = [];
        foreach ($translations as $translation) {
            $attributes = [];
            foreach ($translation as $key => $value) {
                if ($key !== 'locale' && !contains($key, 'id')) {
                    $attributes[$key] = $value;
                }
            }
            $localeTranslations[$translation['locale']] = $attributes;
        }

        return $localeTranslations;
    }
}

if (!function_exists('is_localhost')) {
    function is_localhost(): bool
    {
        return in_array(request()->getHost(), ['localhost', '127.0.0.1']);
    }
}

if (!function_exists('current_customer')) {
    function current_customer(): ?Authenticatable
    {
        $customer = auth()->guard('web')->user();
        return $customer ?: null;
    }
}

if (!function_exists('current_customer_id')) {
    function current_customer_id(): int
    {
        $customer = current_customer();
        return $customer ? current_customer()->id : 0;
    }
}

if (!function_exists('transform_richtext')) {
    function transform_richtext($content)
    {
        // add_responsive_iframe
        $content = $content;
        $content = preg_replace(
            '/(?:<iframe[^>]*)(?:(?:\/>)|(?:>.*?<\/iframe>))/',
            '<div class="aspect-video">$0</div>',
            $content
        );

        // replace_image_url
        if (empty($content)) {
            return $content;
        }

        preg_match_all(
            '/src\s*=\s*"(.+?)"/m',
            $content,
            $matches,
            PREG_SET_ORDER,
            0
        );

        if (empty($matches)) {
            return $content;
        }

        foreach ($matches as $match) {
            $url = $match[1];
            if (contains($url, 'http')) continue;

            $newUrl = static_url('images/'. $url);
            try {
                $content = str_replace($url, $newUrl, $content);
            } catch (\Exception $exception) {
                logger('transform_richtext');
                logger($exception->getMessage());
                logger($url);
                logger('-------------------');
            }
        }
        return $content;
    }
}

if (!function_exists('current_locale')) {
    function current_locale()
    {
        $locale = request()->header('Content-Language');
        if (in_array($locale, config('translatable.locales'))){
            $current_locale = $locale;
        } else {
            $current_locale = config('translatable.fallback_locale');
        }

        return $current_locale;
    }
}
