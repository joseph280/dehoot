import React from 'react';
import SpecialBuild from '@/interfaces/SpecialBuild';
import { AssetCard } from '@/components/assetCard/AssetCard';
import { CrownIcon } from '@/components/icons/CrownIcon';

interface SpecialBuildCardProps {
  asset: SpecialBuild;
  onClick: (asset: SpecialBuild) => void;
}

export function SpecialBuildCard({ asset, onClick }: SpecialBuildCardProps) {
  return (
    <AssetCard type="specialBuild" onClick={() => onClick(asset)}>
      <div className="rounded-lg">
        <div className="absolute rounded-br-lg bg-gray-900 bg-opacity-75 p-1.5 font-bold">
          <CrownIcon className="h-5 w-5" />
        </div>
        <img
          className="h-full w-full"
          src={`/assets/items/specialBuild/${asset.imgUrl}.png`}
          alt="Building"
        />
      </div>
    </AssetCard>
  );
}
