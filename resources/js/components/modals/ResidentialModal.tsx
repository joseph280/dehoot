import React from 'react';
import { Button } from '@/components/ui/button/Button';
import { Modal } from '@/components/ui/modal/Modal';
import { ModalHero } from '@/components/modal/ModalHero';
import { ModalStat } from '@/components/modal/ModalStat';
import { ModalHeader } from '@/components/modal/ModalHeader';
import { ModalDescription } from '@/components/modal/ModalDescription';
import Residential from '@/interfaces/Residential';
import { useAppState } from '@/contexts/stateContext';
import { useStats } from '@/hooks/useStats';
import { Asset } from '@/types/schemas';

interface ResidentialModalProps {
  asset: Residential;
  onClose: () => void;
  unstakeOnClose: (asset: Asset) => void;
}

export function ResidentialModal({ asset, onClose, unstakeOnClose }: ResidentialModalProps) {
  const { state } = useAppState();
  const { transactionProcessing } = useStats();

  const unstakeAsset = async () => {
    unstakeOnClose(asset);
    onClose();
  };

  return (
    <Modal
      position="justify-end"
      padding="p-0"
      bgOpacity="bg-opacity-50"
      onClose={() => onClose()}
    >
      <div className="relative flex max-w-sm -translate-y-12 scale-90 flex-col rounded-xl bg-gradient-to-t from-dehoot-blue-500 to-dehoot-purple-500 pt-24 pb-5 shadow-xl">
        <ModalHero schema="residential" img={asset.imgUrl} onClose={onClose} />
        <ModalHeader name={asset.name} level={asset.level ?? 'N/A'} />

        <ModalDescription
          description={
            asset.description ?? 'No description available on this asset'
          }
        />

        <div className="relative mb-6 grid grid-cols-2 gap-4 px-3">
          <ModalStat stat="Population" value={asset.population} />
          <ModalStat stat="Type" value={asset.type} />
          <ModalStat stat="Water" value={asset.water} unit="daL" />
          <ModalStat stat="Energy" value={asset.energy} unit="GW" />
        </div>

        <div className="px-3">
          <Button
            className="border-red-700 bg-red-500 text-white active:bg-red-700"
            processing={transactionProcessing || state.processing}
            text="Unstake"
            onClick={() => unstakeAsset()}
          />
        </div>
      </div>
    </Modal>
  );
}
