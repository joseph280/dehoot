import React, { useRef } from 'react';
import { Dialog } from '@headlessui/react';
import { motion } from 'framer-motion';
import { classNames } from '@/support/Global';

type ModalPosition = 'justify-end' | 'justify-center';

interface ModalProps {
  blur?: boolean;
  padding?: string;
  bgOpacity?: string;
  position?: ModalPosition;
  children: React.ReactNode;
  onClose: () => void;
}

function Modal({ blur, padding, position, children, bgOpacity, onClose }: ModalProps) {
  const cancelButtonRef = useRef(null);

  return (
    <Dialog
      as={motion.div}
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      className="fixed inset-0 z-10"
      initialFocus={cancelButtonRef}
      open
      onClose={onClose}
    >
      <div
        className={classNames(
          padding ?? '',
          'flex h-full flex-col items-center justify-end text-center sm:block',
        )}
      >
        <Dialog.Overlay
          as={motion.div}
          initial={{ opacity: 0 }}
          animate={{
            opacity: 1,
            transition: { duration: 0.4, ease: [0.36, 0.66, 0.04, 1] },
          }}
          exit={{
            opacity: 0,
            transition: { duration: 0.3, ease: [0.36, 0.66, 0.04, 1] },
          }}
          className={classNames(
            blur ? 'backdrop-blur-md' : '',
            bgOpacity ?? '',
            'fixed inset-0 bg-gray-900',
          )}
        />

        <motion.div
          initial={{ opacity: 0, y: '100%' }}
          animate={{
            opacity: 1,
            y: 0,
            transition: { duration: 0.4, ease: [0.36, 0.66, 0.04, 1] },
          }}
          exit={{
            opacity: 0,
            y: '100%',
            transition: { duration: 0.3, ease: [0.36, 0.66, 0.04, 1] },
          }}
          className={`relative z-0 flex h-full w-full flex-col items-center ${position}`}
        >
          {children}
        </motion.div>
      </div>
    </Dialog>
  );
}

export { Modal };
