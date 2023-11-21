import React from 'react';
import Residential from '@/interfaces/Residential';
import { AssetCard } from '@/components/assetCard/AssetCard';
import { PopulationIcon } from '@/components/icons/PopulationIcon';
import { WaterIcon } from '../icons/WaterIcon';
import { LightningIcon } from '../icons/LightningIcon';

interface ResidentialCardProps {
  asset: Residential;
  onClick: (asset: Residential) => void;
}

export function ResidentialCard({ asset, onClick }: ResidentialCardProps) {
  return (
    <div className="inline-flex select-none justify-center">
      <AssetCard type="residential" onClick={() => onClick(asset)}>
        <div className="absolute flex rounded-br-lg bg-gray-900 bg-opacity-75 pl-0.5 pr-0.5 font-Poppins font-bold text-white">
          <div className="mr-1 flex">
            <PopulationIcon className="mr-1 h-4 w-4 self-center" />
            <span className="text-sm">{asset.population}</span>
          </div>
          <div className="mr-1 flex">
            <WaterIcon className="mr-1 h-4 w-4 self-center" />
            <span className="text-sm">{asset.water}</span>
          </div>
          <div className="mr-1 flex">
            <LightningIcon className="mr-1 h-4 w-4 self-center" />
            <span className="text-sm">{asset.energy}</span>
          </div>
        </div>
        <div className="absolute bottom-0 w-full bg-gray-900 bg-opacity-50 py-1 font-Poppins font-bold text-white">
          <span>Level {asset.level}</span>
        </div>
        <img
          className="h-full w-full"
          src={`/assets/items/residential/${asset.imgUrl}.png`}
          alt="Building"
        />
      </AssetCard>
    </div>
  );
}
