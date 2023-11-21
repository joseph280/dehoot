import React from 'react';

interface ModalStatProps {
  stat: string;
  value?: string;
  unit?: string;
}

export function ModalStat({ stat, value, unit }: ModalStatProps) {
  return (
    <div className="relative rounded-md px-2 py-1">
      <p className="relative z-30 text-left text-sm font-semibold text-slate-200">
        {stat}
      </p>
      <p className="relative z-30 text-left text-xl font-bold text-slate-50">
        {value ?? 'N/A'}{' '}{unit ?? ''}
      </p>
      <div className="absolute inset-0 z-20 h-full w-full rounded-md bg-neutral-800 opacity-50 outline-none drop-shadow-md backdrop-blur-md" />
    </div>
  );
}
