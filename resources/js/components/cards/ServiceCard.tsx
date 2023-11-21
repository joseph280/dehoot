import React from 'react';
import { AssetCard } from '@/components/assetCard/AssetCard';
import Service from '@/interfaces/Service';
import { WaterIcon } from '@/components/icons/WaterIcon';
import { LightningIcon } from '@/components/icons/LightningIcon';

interface ServiceCardProps {
  asset: Service;
  onClick: (asset: Service) => void;
}

export function ServiceCard({ asset, onClick }: ServiceCardProps) {

  const displayServiceIcon = () => {
    if (asset.type === 'Water')
      return <WaterIcon className="mr-1 h-4 w-4 self-center fill-white" />;
    if (asset.type === 'Energy')
      return <LightningIcon className="mr-1 h-4 w-4 self-center fill-white" />;
    return <div />;
  };

  return (
    <AssetCard type="service" onClick={() => onClick(asset)}>
      <div className="absolute flex rounded-br-lg bg-gray-900 bg-opacity-75 py-[1.25px] pl-1 pr-1.5 font-bold text-white">
        {displayServiceIcon()}
        <span>{asset.capacity}</span>
      </div>
      <img
        className="h-full w-full"
        src={`/assets/items/service/${asset.imgUrl}.png`}
        alt="Service"
      />
    </AssetCard>
  );
}
