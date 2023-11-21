import React from 'react';
import { XButton } from '@/components/ui/button/XButton';

interface ModalHeroProps {
  img: string;
  schema: 'residential' | 'specialBuild' | 'service';
  onClose: () => void;
}

export function ModalHero({ img, schema, onClose }: ModalHeroProps) {
  return (
    <div className="absolute top-0 z-20 inline-flex w-full -translate-y-40 flex-col">
      <div className="flex justify-center">
        <XButton onClick={onClose} />
      </div>
      <div className="flex justify-center">
        <img
          className="-mt-16 h-72 w-72"
          src={`/assets/items/${schema}/${img}.png`}
          alt="Asset"
        />
      </div>
    </div>
  );
}
