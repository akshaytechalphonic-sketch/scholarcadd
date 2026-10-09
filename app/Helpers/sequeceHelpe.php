<?php

use Illuminate\Support\Facades\DB;

if (! function_exists('generate_incremental_sequence')) {

    function generate_incremental_sequence(
        string $model,
        string $column,
        string $prefix,
        int $limit = 6
    ): string {

        // Get last sequence
        $lastSequence = $model::where($column, 'like', $prefix . '%')
            ->orderBy($column, 'desc')
            ->value($column);

        $nextNumber = 1;

        if ($lastSequence) {
            // Extract numeric part
            $number = (int) preg_replace('/\D/', '', $lastSequence);
            $nextNumber = $number + 1;
        }

        return $prefix . '-' . str_pad(
            $nextNumber,
            $limit,
            '0',
            STR_PAD_LEFT
        );
    }
}
