<?php

namespace App\Http\Transformers;

use App\Models\UserMuseAccount;
use Carbon\Carbon;

class UserAccountTransformer extends BaseTransformer
{

    public function transform(UserMuseAccount $account)
    {
        $available_days = Carbon::now()->diffInDays(Carbon::parse($account->end_time), false);
        return [
            'id'            => $account->id,
            'end_time'      => $account->end_time,
            'available_days' => $available_days > 0 ? $available_days : 0,
        ];
    }
}
