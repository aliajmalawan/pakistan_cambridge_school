<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;

/**
 * Handles the institution logo uploaded from Admin → Site Settings.
 *
 * A single uploaded image is resampled into every size the site needs
 * (navbar, footer, favicon, Open Graph) as PNG plus WebP. Derivatives live in
 * /uploads/logo so the shipped default in /assets/img is never overwritten —
 * removing the custom logo restores the original crest instantly.
 */
final class LogoService
{
    public const DIR = 'logo';

    /** size => also emit a WebP twin */
    public const SIZES = [
        32  => false,   // favicon
        48  => false,   // navbar, small screens
        96  => true,    // navbar, footer
        192 => true,    // apple-touch-icon
        512 => true,    // Open Graph / social preview
    ];

    public static function hasCustom(): bool
    {
        return Setting::value('logo_custom') === '1'
            && is_file(UPLOAD_PATH . '/' . self::DIR . '/logo-96.png');
    }

    public static function version(): string
    {
        return Setting::value('logo_version', '1');
    }

    /**
     * Validate and process an uploaded logo.
     *
     * @return string|null null on success, otherwise a human-readable error
     */
    public static function store(string $field): ?string
    {
        if (empty($_FILES[$field]['name']) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null; // nothing submitted — not an error
        }

        $file = $_FILES[$field];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
                ? 'The logo file is larger than the server allows. Use an image under 4 MB.'
                : 'The logo upload failed (error code ' . $file['error'] . '). Please try again.';
        }
        if ($file['size'] > UPLOAD_MAX_BYTES) {
            return 'The logo must be smaller than 4 MB.';
        }

        $info = @getimagesize($file['tmp_name']);
        if ($info === false) {
            return 'That file is not a readable image. Upload a PNG, JPG or WebP.';
        }

        [$width, $height, $type] = $info;
        if ($width < 128 || $height < 128) {
            return 'The logo is too small (' . $width . '×' . $height . '). Upload an image at least 128×128 pixels — 512×512 or larger is best.';
        }

        $source = self::readImage($file['tmp_name'], $type);
        if ($source === null) {
            return 'Only PNG, JPG, WebP and GIF images are supported.';
        }

        $dir = UPLOAD_PATH . '/' . self::DIR;
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            imagedestroy($source);
            return 'Could not create the upload folder. Check permissions on /uploads.';
        }

        foreach (self::SIZES as $size => $withWebp) {
            $canvas = self::square($source, $width, $height, $size);
            imagepng($canvas, $dir . '/logo-' . $size . '.png', 9);
            if ($withWebp && function_exists('imagewebp')) {
                imagewebp($canvas, $dir . '/logo-' . $size . '.webp', 88);
            }
            imagedestroy($canvas);
        }

        imagedestroy($source);

        Setting::put('logo_custom', '1');
        Setting::put('logo_version', (string) time());

        return null;
    }

    /** Delete every derivative and fall back to the bundled crest. */
    public static function remove(): void
    {
        $dir = UPLOAD_PATH . '/' . self::DIR;
        foreach (self::SIZES as $size => $withWebp) {
            @unlink($dir . '/logo-' . $size . '.png');
            if ($withWebp) {
                @unlink($dir . '/logo-' . $size . '.webp');
            }
        }
        Setting::put('logo_custom', '0');
        Setting::put('logo_version', (string) time());
    }

    private static function readImage(string $path, int $type): ?\GdImage
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
     * Fit the source inside a transparent square canvas without distortion,
     * so a non-square upload still slots into the circular/square logo frames.
     */
    private static function square(\GdImage $source, int $srcW, int $srcH, int $size): \GdImage
    {
        $canvas = imagecreatetruecolor($size, $size);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefilledrectangle($canvas, 0, 0, $size, $size, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagealphablending($canvas, true);

        $scale = min($size / $srcW, $size / $srcH);
        $dstW = max(1, (int) round($srcW * $scale));
        $dstH = max(1, (int) round($srcH * $scale));
        $dstX = (int) (($size - $dstW) / 2);
        $dstY = (int) (($size - $dstH) / 2);

        imagecopyresampled($canvas, $source, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
        return $canvas;
    }
}
