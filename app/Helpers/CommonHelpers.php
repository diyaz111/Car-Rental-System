<?php

namespace App\Helpers;

use Carbon\Carbon;

class CommonHelpers
{
    public static function response(
        bool $status,
        ?string $referenceId,
        int $code,
        string $message,
        $data = null,
        ?Carbon $timestamp = null
    ): array {
        return [
            'status' => $status ? 'OK' : 'ERROR',
            'ref_id' => $referenceId,
            'code' => $code,
            'timestamp' => $timestamp ? $timestamp->toIso8601String() : now()->toIso8601String(),
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function formatRupiah($amount)
    {
        return 'Rp. ' . number_format($amount, 0, ',', '.');
    }
}
