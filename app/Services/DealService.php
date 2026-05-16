<?php

namespace App\Services;

use App\Models\Deal;
use Illuminate\Support\Facades\Auth;

class DealService
{
    public function create(array $data): Deal
    {
        $data['assigned_to'] = $data['assigned_to'] ?? Auth::id();

        return Deal::create($data);
    }

    public function update(Deal $deal, array $data): Deal
    {
        $deal->update($data);

        return $deal->fresh();
    }

    public function updateStage(Deal $deal, string $stage): Deal
    {
        $oldStage = $deal->stage;

        $deal->update([
            'stage' => $stage,
            'actual_close_date' => in_array($stage, ['won', 'lost']) ? now() : null,
        ]);

        // Fire event (optional — future use)
        // event(new DealStageChanged($deal, $oldStage, $stage));

        return $deal->fresh();
    }
}
