<?php

namespace Domain\Player\Controllers\Api;

use Domain\Player\Models\Player;
use App\Http\Controllers\Controller;
use Domain\Log\Enums\TransactionStatus;
use Domain\Asset\Repositories\AssetsRepository;
use Domain\Player\Repositories\BalancesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Domain\Asset\Actions\GetServiceConsumptionAction;

class PlayerStatsController extends Controller
{
    public function __invoke(AssetsRepository $assetsRepository): JsonResponse
    {
        /** @var Player */
        $player = auth()->user();

        $balances = (new BalancesRepository())->getBalances($player);

        $consumption = GetServiceConsumptionAction::execute($player->stakedAssets);

        $population = $player->stakedAssets->getTotalPopulation();

        $transactionLog = $player->transactionLogs()
            ->where('status', TransactionStatus::Processing)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'hootBalance' => $balances->hootBalance,
                'waxBalance' => $balances->waxBalance,
                'processing' => isset($transactionLog),
                'consumption' => $consumption,
                'population' => $population,
            ],
        ]);
    }
}
