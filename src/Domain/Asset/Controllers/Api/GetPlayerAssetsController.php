<?php

namespace Domain\Asset\Controllers\Api;

use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Asset\Collections\AssetCollection;
use Domain\Asset\Repositories\AssetsRepository;

class GetPlayerAssetsController extends Controller
{
    public function __invoke(AssetsRepository $assetsRepository): JsonResponse
    {
        /** @var Player */
        $player = auth()->user();

        /** @var AssetCollection<Asset> */
        $assets = $assetsRepository
            ->getPlayerAssets($player)
            ->loadOnStaking($player->stakedAssets)
            ->loadRowsAndColumns()
            ->loadStakedBalance($player);

        return response()->json([
            'success' => true,
            'data' => [
                'assets' => $assets,
                'stakedAssets' => [
                    'residentialBuildings' => $assets->residentialBuildings()->whereOnStaking(),
                    'specialBuildings' => $assets->specialBuildings()->whereOnStaking(),
                    'serviceBuildings' => $assets->serviceBuildings()->whereOnStaking(),
                ],
                'unstakedAssets' => [
                    'residentialBuildings' => $assets->residentialBuildings()->whereOnStaking(false),
                    'specialBuildings' => $assets->specialBuildings()->whereOnStaking(false),
                    'serviceBuildings' => $assets->serviceBuildings()->whereOnStaking(false),
                ],
                'stakingLimit' => $player->stakedAssets->stakingLimit(),
            ],
            'metadata' => [
                'residentialBuildingsCount' => $assets->residentialBuildings()->count(),
                'serviceBuildingsCount' => $assets->serviceBuildings()->count(),
                'specialBuildingsCount' => $assets->specialBuildings()->count(),
            ],
        ]);
    }
}
