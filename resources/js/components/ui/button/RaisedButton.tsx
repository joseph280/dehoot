import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { classNames } from '@/support/Global';

interface RaisedButtonProps {
  children: React.ReactNode;
  processing?: boolean;
  disabled?: boolean;
  onClick: () => void;
  className?: string;
}

export function RaisedButton({
  children,
  className,
  processing,
  disabled,
  onClick,
}: RaisedButtonProps) {
  const [isProcessing, setIsProcessing] = useState<boolean>(false);

  const handleClick = () => {
    onClick();

    if (processing) {
      setIsProcessing(true);
    }
  };

  return (
    <motion.button
      disabled={disabled || isProcessing}
      whileTap={{
        scale: disabled ? 1.0 : 0.95,
      }}
      className={classNames(
        className ?? '',
        'mt-1 inline-flex w-full select-none items-center justify-center rounded-lg border border-x-2 border-b-4 py-3 shadow-lg focus:outline-none disabled:border-gray-900 disabled:bg-gray-700 disabled:text-slate-400',
      )}
      onClick={() => handleClick()}
    >
      {children}
    </motion.button>
  );
}
