import React from 'react';
import SpecialBuild from '@/interfaces/SpecialBuild';
import { SpecialBuildCard } from '@/components/cards/SpecialBuildCard';
import { Modal } from '../modal/Modal';
import BuildHeader from './BuildHeader';
import { AssetMessageDisplay } from '@/components/helpers/AssetMessageDisplay';
import { useAppState } from '@/contexts/stateContext';
import { classNames } from '@/support/Global';

interface BuildSpecialProps {
  unstakedAssets: SpecialBuild[];
  count: number;
  onClose: () => void;
  onCloseWithAsset: (special: SpecialBuild) => void;
}

export default function BuildSpecials({
  unstakedAssets,
  count,
  onClose,
  onCloseWithAsset,
}: BuildSpecialProps) {
  const { state } = useAppState();

  const handleSelectedAsset = (asset: SpecialBuild) => {
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
          title="Specials"
          description="Select one of your buildings"
          onClose={onClose}
        />
        {unstakedAssets.length === 0 && (
          <AssetMessageDisplay
            type="specialbuild"
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
            {unstakedAssets.length > 0 &&
              unstakedAssets.map(asset => (
                <SpecialBuildCard
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
