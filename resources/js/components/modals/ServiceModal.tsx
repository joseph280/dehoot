import React from 'react';
import Service, { ServiceType } from '@/interfaces/Service';
import { Modal } from '../ui/modal/Modal';
import { ModalDescription } from '../modal/ModalDescription';
import { ModalHeader } from '../modal/ModalHeader';
import { ModalHero } from '../modal/ModalHero';
import { ModalStat } from '../modal/ModalStat';
import { LightningIcon } from '../icons/LightningIcon';
import { WaterIcon } from '../icons/WaterIcon';
import { Button } from '../ui/button/Button';
import { useStats } from '@/hooks/useStats';
import { useAppState } from '@/contexts/stateContext';
import { Asset } from '@/types/schemas';

interface ServiceModalProps {
  asset: Service;
  type: ServiceType;
  onClose: () => void;
  unstakeOnClose: (asset: Asset) => void;
}

const styles = {
  Energy: {
    gradient: 'from-yellow-700 to-yellow-500',
  },
  Water: {
    gradient: 'from-sky-500 to-sky-700',
  },
  Transport: {
    gradient: 'from-slate-500 to-slate-700',
  },
};

export function ServiceModal({
  asset,
  type,
  onClose,
  unstakeOnClose,
}: ServiceModalProps) {
  const { transactionProcessing } = useStats();
  const { state } = useAppState();

  const renderIcon = () => {
    if (asset.type === 'Energy') return <LightningIcon />;
    if (asset.type === 'Water') return <WaterIcon />;

    return <div />;
  };

  const setUnitType = () => {
    if (asset.type === 'Energy') return 'GW';
    if (asset.type === 'Water') return 'daL';
    return '';
  };

  const setCapacityType = () => {
    if (asset.type === 'Energy') return 'Energy';
    if (asset.type === 'Water') return 'Water';
    return 'Capacity';
  };

  const unstakeAsset = () => {
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
      <div
        className={`relative flex max-w-sm -translate-y-12 scale-90 flex-col rounded-xl bg-gradient-to-t pt-24 pb-5 shadow-xl ${styles[type].gradient}`}
      >
        <ModalHero schema="service" img={asset.imgUrl} onClose={onClose} />
        <ModalHeader>
          <div className="flex items-center justify-center">
            {renderIcon()}
            <span className="mx-2">{asset.name}</span>
            {renderIcon()}
          </div>
        </ModalHeader>

        <ModalDescription
          description={asset.description ?? 'No description available on this asset'}
        />

        <div className="relative mb-6 grid grid-cols-2 gap-4 px-3">
          <ModalStat stat="Type" value={asset.type} />
          <ModalStat
            stat={setCapacityType()}
            value={asset.capacity}
            unit={setUnitType()}
          />
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
