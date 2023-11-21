import React from 'react';
import { XIcon } from '@heroicons/react/solid';
import { classNames } from '@/support/Global';

interface XButtonProps {
  className?: string;
  onClick: () => void;
}

export function XButton({ className, onClick }: XButtonProps) {
  return (
    <div
      className={classNames(
        className ?? '',
        'relative z-30 h-8 w-8 cursor-pointer rounded-full border border-b-2 border-gray-900 bg-red-500 p-1 transition-transform duration-75 active:scale-150',
      )}
      onClick={onClick}
    >
      <XIcon className="h-full w-full text-white" />
    </div>
  );
}
