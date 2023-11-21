import React from 'react';
import { motion } from 'framer-motion';

const item = {
  hidden: { y: 20, opacity: 0 },
  visible: {
    y: 0,
    opacity: 1,
  },
};

const gradients = {
  residential: {
    gradient: 'from-dehoot-purple-500 via-dehoot-sky-500 to-dehoot-sky-500',
  },
  service: {
    gradient: 'from-dehoot-purple-500 via-dehoot-sky-500 to-dehoot-sky-500',
  },
  specialBuild: {
    gradient: 'from-dehoot-purple-500 via-dehoot-sky-500 to-dehoot-sky-500',
  },
};

interface CardProps {
  type: 'residential' | 'specialBuild' | 'service';
  children: React.ReactNode;
  onClick?: () => void;
}

export function AssetCard({ type, children, onClick }: CardProps) {
  return (
    <motion.div
      variants={item}
      className={`relative font-Poppins aspect-square max-h-44 cursor-pointer rounded-lg bg-gradient-to-t ${gradients[type].gradient}`}
      onClick={onClick}
    >
      {children}
    </motion.div>
  );
}
