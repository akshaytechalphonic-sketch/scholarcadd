<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Course\Models\PromoCode;

class ExpirePromoCodes extends Command
{
    protected $signature = 'promo:expire';
    protected $description = 'Expire promo codes past end_date';

    public function handle()
    {
        // 🔥 Capture updated count
        $updated = PromoCode::where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'expired']);

        $this->info("✅ Expired {$updated} promo codes");

        return self::SUCCESS; 
    }
}
