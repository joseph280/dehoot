import React from 'react';
import { Modal } from '@/components/ui/modal/Modal';
import BuildHeader from './BuildHeader';
import { AssetSchema } from '@/types/schemas';

interface BuildSelectionProps {
  onClose: () => void;
  openMenu: (type: AssetSchema) => void;
}

export default function BuildSelection({
  onClose,
  openMenu,
}: BuildSelectionProps) {
  return (
    <Modal
      position="justify-end"
      padding="p-0"
      bgOpacity="bg-opacity-50"
      onClose={() => onClose()}
    >
      <div className="relative flex w-full max-w-lg flex-col rounded-t-lg bg-slate-800 px-5 pt-6 pb-12 shadow-xl">
        <BuildHeader
          title="Build Mode"
          description="Select building type"
          onClose={onClose}
        />
        <div className="grid select-none grid-cols-3 gap-2">
          <div
            className="rounded-xl border border-slate-50 py-4 md:py-8"
            onClick={() => openMenu('residential')}
          >
            <img className="mx-auto" src="/assets/icons/city.svg" alt="city" />
            <span className="text-sm font-semibold text-white">
              Residentials
            </span>
          </div>
          <div
            className="rounded-xl border border-slate-50 py-4 md:py-8"
            onClick={() => openMenu('service')}
          >
            <img
              className="mx-auto"
              src="/assets/icons/energy.svg"
              alt="energy"
            />
            <span className="text-sm font-semibold text-white">Services</span>
          </div>
          <div
            className="rounded-xl border border-slate-50 py-4 md:py-8"
            onClick={() => openMenu('specialbuild')}
          >
            <img
              className="mx-auto"
              src="/assets/icons/crown.svg"
              alt="crown"
            />
            <span className="text-sm font-semibold text-white">Specials</span>
          </div>
        </div>
      </div>
    </Modal>
  );
}
