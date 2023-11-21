import React from 'react';
import { motion } from 'framer-motion';
import { classNames } from '../../../support/Global';
import { ProcessIcon } from '@/components/icons/ProcessIcon';

interface ButtonProps {
  text: string;
  className?: string;
  spanClassName?: string;
  disabled?: boolean;
  processing?: boolean;
  onClick: () => void;
}

function Button({
  className,
  spanClassName,
  text,
  disabled,
  processing,
  onClick,
}: ButtonProps) {
  return (
    <motion.button
      transition={{ ease: 'easeOut', duration: 0.1 }}
      whileTap={{ scale: disabled ? 1.0 : 1.1 }}
      disabled={disabled || processing}
      className={classNames(
        className ?? '',
        disabled ? 'bg-gray-600' : '',
        'inline-flex w-full select-none items-center justify-center rounded-xl border-x border-t border-b-4 py-4 text-2xl font-bold focus:outline-none',
      )}
      onClick={() => onClick()}
    >
      {processing && <ProcessIcon />}
      <span className={spanClassName ?? ''}>
        {processing ? 'Processing' : text}
      </span>
    </motion.button>
  );
}

export { Button };
