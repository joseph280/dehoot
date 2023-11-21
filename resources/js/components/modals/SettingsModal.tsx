import React from 'react';
import { Inertia } from '@inertiajs/inertia';
import { Modal } from '@/components/ui/modal/Modal';
import { Button } from '../ui/button/Button';
import BuildHeader from '../ui/build/BuildHeader';

interface SettingsModalProps {
  onClose: () => void;
}

export default function SettingsModal({ onClose }: SettingsModalProps) {

  const handleLogout = () => {
    Inertia.post(route('logout'));
    onClose();
  }

  return (
    <Modal
      position="justify-end"
      padding="p-0"
      bgOpacity="bg-opacity-50"
      onClose={() => onClose}
    >
      <div className="relative flex w-full max-w-lg flex-col rounded-t-lg bg-slate-800 px-5 pt-6 pb-12 shadow-xl">
        <BuildHeader title="Game settings" description="" onClose={onClose} />
        <Button
          className="border-red-700 bg-red-500 text-white active:bg-red-700"
          text="Logout"
          onClick={() => handleLogout()}
        />
      </div>
    </Modal>
  );
}
