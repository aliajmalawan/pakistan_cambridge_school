<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Resizes and re-encodes uploaded images so a photograph saved as a 1.5 MB PNG
 * does not end up as the homepage hero. Runs on every CMS upload.
 */
final class ImageService
{
    /** Longest edge, by upload context. */
    public const WIDTH_HERO    = 1920; // sliders
    public const WIDTH_CONTENT = 1600; // news, pages, gallery
    public const WIDTH_PORTRAIT = 800; // faculty
    public const WIDTH_AVATAR   = 400; // testimonials

    private const JPEG_QUALITY = 82;
    private const WEBP_QUALITY = 82;

    /**
     * Process an uploaded temp file into $dir, returning the stored relative
     * path (e.g. "sliders/20260727-1200-ab12cd34.jpg"), or null on failure.
     */
    public static function process(string $tmpPath, string $dir, int $maxWidth): ?string
    {
        $info = @getimagesize($tmpPath);
        if ($info === false) {
            return null;
        }
        [$width, $height, $type] = $info;

        $source = self::read($tmpPath, $type);
        if ($source === null) {
            return null;
        }

        // Scale down only — never upscale a small image
        $scale = $maxWidth > 0 && $width > $maxWidth ? $maxWidth / $width : 1.0;
        $targetW = (int) round($width * $scale);
        $targetH = (int) round($height * $scale);

        $hasAlpha = in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)
            && self::usesTransparency($source, $width, $height);

        $canvas = imagecreatetruecolor($targetW, $targetH);
        if ($hasAlpha) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            imagefilledrectangle($canvas, 0, 0, $targetW, $targetH, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        } else {
            // Flatten onto white so a transparent-but-unused PNG can become a JPEG
            imagefilledrectangle($canvas, 0, 0, $targetW, $targetH, imagecolorallocate($canvas, 255, 255, 255));
        }
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetW, $targetH, $width, $height);
        imagedestroy($source);

        $target = UPLOAD_PATH . '/' . $dir;
        if (!is_dir($target) && !mkdir($target, 0775, true) && !is_dir($target)) {
            imagedestroy($canvas);
            return null;
        }

        // Transparency must stay PNG; everything else is far smaller as JPEG
        $ext = $hasAlpha ? 'png' : 'jpg';
        $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $path = $target . '/' . $name;

        $saved = $hasAlpha
            ? imagepng($canvas, $path, 9)
            : imagejpeg($canvas, $path, self::JPEG_QUALITY);

        // WebP twin for browsers that accept it (served opportunistically by views)
        if ($saved && function_exists('imagewebp')) {
            imagewebp($canvas, preg_replace('/\.\w+$/', '.webp', $path), self::WEBP_QUALITY);
        }

        imagedestroy($canvas);

        return $saved ? $dir . '/' . $name : null;
    }

    /** Optimise a file already stored under /uploads, replacing it in place. */
    public static function optimizeExisting(string $relativePath, int $maxWidth): ?string
    {
        $full = UPLOAD_PATH . '/' . $relativePath;
        if (!is_file($full)) {
            return null;
        }
        $dir = dirname($relativePath);
        $new = self::process($full, $dir === '.' ? '' : $dir, $maxWidth);
        if ($new !== null && $new !== $relativePath) {
            @unlink($full);
        }
        return $new;
    }

    private static function read(string $path, int $type): ?\GdImage
    {
        $image = match ($type) {
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_GIF  => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default        => false,
        };
        return $image instanceof \GdImage ? $image : null;
    }

    /**
     * Sample the image for genuinely transparent pixels. A photograph exported
     * as PNG carries an alpha channel that is never used — detecting that lets
     * us re-encode it as a much smaller JPEG.
     */
    private static function usesTransparency(\GdImage $image, int $width, int $height): bool
    {
        $stepX = max(1, (int) ($width / 60));
        $stepY = max(1, (int) ($height / 60));
        for ($x = 0; $x < $width; $x += $stepX) {
            for ($y = 0; $y < $height; $y += $stepY) {
                $alpha = (imagecolorat($image, $x, $y) >> 24) & 0x7F;
                if ($alpha > 8) {
                    return true;
                }
            }
        }
        return false;
    }
}
