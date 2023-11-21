<?php

namespace Domain\Player\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Asset\Actions\GetRewardAction;

class GetPlayerRewardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $player = auth()->user();

        $stakedAssets = $player->stakedAssets->whereStakedAtOlderThanOneDay();

        $reward = GetRewardAction::execute($stakedAssets, $player);

        return response()->json([
            'success' => true,
            'data' => [
                'reward' => $reward,
            ],
        ]);
    }
}
