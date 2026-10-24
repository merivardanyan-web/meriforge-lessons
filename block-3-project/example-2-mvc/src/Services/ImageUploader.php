<?php
declare(strict_types=1);

namespace App\Services;

use finfo;

/** Դաս 7-ի uploadImage()-ը՝ դասով, թղթապանակը՝ կոնստրուկտորով — PHP Դաս 14 */
class ImageUploader
{
    private const MAX_BYTES = 2 * 1024 * 1024;
    private const TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    public function __construct(private readonly string $dir) {}

    /** @return array{0: ?string, 1: ?string} [ֆայլի անուն, սխալ] */
    public function upload(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return [null, 'Ֆայլը չբեռնվեց'];
        }

        if ($file['size'] > self::MAX_BYTES) {
            return [null, 'Ֆայլը 2 ՄԲ-ից մեծ է'];
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

        if (!isset(self::TYPES[$mime]) || getimagesize($file['tmp_name']) === false) {
            return [null, 'Թույլատրվում են միայն JPG, PNG, WebP նկարներ'];
        }

        $name = bin2hex(random_bytes(8)) . '.' . self::TYPES[$mime];

        if (!move_uploaded_file($file['tmp_name'], rtrim($this->dir, '/') . '/' . $name)) {
            return [null, 'Չհաջողվեց պահպանել'];
        }

        return [$name, null];
    }
}
