import React from 'react';
import { Modal } from '../modal/Modal';
import BuildHeader from './BuildHeader';
import Service from '@/interfaces/Service';
import { ServiceCard } from '@/components/cards/ServiceCard';
import { AssetMessageDisplay } from '@/components/helpers/AssetMessageDisplay';
import { useAppState } from '@/contexts/stateContext';
import { classNames } from '@/support/Global';

interface BuildSpecialProps {
  unstakedAssets: Service[];
  count: number;
  onClose: () => void;
  onCloseWithAsset: (service: Service) => void;
}

export default function BuildServices({
  unstakedAssets,
  count,
  onClose,
  onCloseWithAsset,
}: BuildSpecialProps) {
  const { state } = useAppState();

  const handleSelectedAsset = (asset: Service) => {
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
          title="Services"
          description="Select one of your buildings"
          onClose={onClose}
        />
        {unstakedAssets.length === 0 && (
          <AssetMessageDisplay
            type="service"
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
              <ServiceCard
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
