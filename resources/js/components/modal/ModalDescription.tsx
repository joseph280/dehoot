import React from 'react';

interface ModalDescriptionProps {
  description: string;
}

export function ModalDescription({ description }: ModalDescriptionProps) {
  return (
    <div className="relative mb-4 px-8 py-6">
      <p className="relative z-30 text-sm text-slate-50">{description}</p>
      <div className="absolute inset-0 z-20 h-full w-full bg-neutral-800 opacity-50 backdrop-blur-md" />
    </div>
  );
}
