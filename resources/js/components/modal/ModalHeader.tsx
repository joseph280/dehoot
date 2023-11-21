import React, { ReactNode } from 'react';

interface ModalHeaderProps {
  name?: string;
  children?: ReactNode;
  level?: string;
}

export function ModalHeader({ name, level, children }: ModalHeaderProps) {
  return (
    <div className="mb-4">
      <h1 className="text-center text-2xl font-bold px-4 text-slate-50 drop-shadow-xl">
        {children}
        {name}
      </h1>
      {level && (
        <h2 className="text-center font-bold text-slate-50">Level {level}</h2>
      )}
    </div>
  );
}
