import React from 'react';
import BuildHeader from './BuildHeader';
import { ResidentialCard } from '@/components/cards/ResidentialCard';
import { Modal } from '../modal/Modal';
import Residential from '@/interfaces/Residential';
import { AssetMessageDisplay } from '@/components/helpers/AssetMessageDisplay';
import { useAppState } from '@/contexts/stateContext';
import { classNames } from '@/support/Global';

interface BuildResidentialsProps {
  stakedAssets: Residential[];
  unstakedAssets: Residential[];
  stakingLimit: number;
  count: number;
  onClose: () => void;
  onCloseWithAsset: (residential: Residential) => void;
}

export default function BuildResidentials({
  stakedAssets,
  unstakedAssets,
  stakingLimit,
  count,
  onClose,
  onCloseWithAsset,
}: BuildResidentialsProps) {
  const { state } = useAppState();

  const handleSelectedAsset = (asset: Residential) => {
    if (!state.processing) {
      onCloseWithAsset(asset);
    }
  };

  return (
    <Modal
      position="justify-end"
      padding="p-0"
      bgOpacity="bg-opacity-50"
      onClose={() => onClose()}
    >
      <div className="relative flex w-full max-w-2xl flex-col rounded-t-lg bg-slate-800 px-5 pt-6 pb-9 shadow-xl">
        <BuildHeader
          title="Residentials"
          description="Select one of your buildings"
          onClose={onClose}
          value={`${stakedAssets.length} / ${stakingLimit}`}
        />
        {unstakedAssets.length === 0 && (
          <AssetMessageDisplay
            type="residential"
            count={count}
            length={unstakedAssets.length}
          />
        )}
        {unstakedAssets.length > 0 && (
          <div
            className={classNames(
              'grid h-[450px] w-full grid-cols-2 place-content-start gap-3 overflow-y-auto md:grid-cols-3',
              state.processing ? 'animate-pulse' : '',
            )}
          >
            {unstakedAssets.map(asset => (
              <ResidentialCard
                key={asset.assetId}
                asset={asset}
                onClick={handleSelectedAsset}
              />
            ))}
          </div>
        )}
      </div>
    </Modal>
  );
}
