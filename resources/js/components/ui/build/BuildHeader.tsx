import React from 'react';
import { XButton } from '../button/XButton';

interface BuildHeaderProps {
  title: string;
  description: string;
  value?: string;
  onClose: () => void;
}

export default function BuildHeader({
  title,
  description,
  value,
  onClose,
}: BuildHeaderProps) {
  return (
    <>
      <div className="mb-5 flex flex-col font-Poppins">
        <div className="flex justify-between">
          <h1 className="text-2xl font-bold text-white">{title}</h1>
          <XButton onClick={onClose} />
        </div>
        <div className="flex justify-between">
          <p className="text-base font-medium text-white">{description}</p>
          <p className="text-base font-medium text-white">{value}</p>
        </div>
      </div>
      <div className="mb-5 h-1 w-full bg-gradient-to-r from-purple-500 to-blue-500" />
    </>
  );
}
