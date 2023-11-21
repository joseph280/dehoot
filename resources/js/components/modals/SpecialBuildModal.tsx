import React from 'react';
import { Modal } from '@/components/ui/modal/Modal';
import { ModalHero } from '@/components/modal/ModalHero';
import { ModalStat } from '@/components/modal/ModalStat';
import { ModalDescription } from '@/components/modal/ModalDescription';
import SpecialBuilding from '@/interfaces/SpecialBuild';
import { ModalHeader } from '@/components/modal/ModalHeader';
import { Button } from '@/components/ui/button/Button';
import { useStats } from '@/hooks/useStats';
import { useAppState } from '@/contexts/stateContext';
import { Asset } from '@/types/schemas';

interface SpecialBuildModalProps {
  onClose: () => void;
  unstakeOnClose: (asset: Asset) => void;
  asset: SpecialBuilding;
}

export function SpecialBuildModal({
  asset,
  unstakeOnClose,
  onClose,
}: SpecialBuildModalProps) {
  const { transactionProcessing } = useStats();
  const { state } = useAppState();

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
      <div className="relative flex max-w-sm -translate-y-20 scale-90 flex-col rounded-xl bg-gradient-to-t from-dehoot-purple-500 to-amber-500 pt-24 pb-5 shadow-xl">
        <ModalHero schema="specialBuild" img={asset.imgUrl} onClose={onClose} />
        <ModalHeader name={asset.name} />

        <ModalDescription
          description={
            asset.description ?? 'No description available for this asset'
          }
        />

        <div className="relative mb-6 grid grid-cols-2 gap-4 px-3">
          <ModalStat stat="Season" value={asset.season} />
          <ModalStat stat="Type" value={asset.type} />
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
