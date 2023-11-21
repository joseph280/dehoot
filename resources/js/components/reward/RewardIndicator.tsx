import React from 'react';
import { motion } from 'framer-motion';
import { useReward } from '@/hooks/useReward';

export function RewardIndicator() {
  const { reward } = useReward();

  return (
    <motion.div
      animate={{
        boxShadow: [
          '0px 0px 10px 0px rgba(251, 191, 36, 1)',
          '0px 0px 10px 0px rgba(178, 37, 217, 1)',
          '0px 0px 10px 0px rgba(66, 136, 215, 1)',
          '0px 0px 10px 0px rgba(251, 191, 36, 1)',
        ],
      }}
      transition={{ duration: 1, repeat: Infinity }}
      hidden={!reward || reward?.total.value <= 0}
      className="absolute z-50 -translate-y-24 rounded-lg bg-slate-800 px-3 py-2 text-center font-Poppins font-bold text-amber-400 drop-shadow"
    >
      <span className="text-sm">{reward?.total.formattedShorten} </span>
      <span className="text-xs">Hoot</span>
    </motion.div>
  );
}
