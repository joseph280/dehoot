import React from 'react';
import { classNames } from '@/support/Global';

interface LightningIconProps {
  className?: string;
}

export function LightningIcon({ className }: LightningIconProps) {
  return (
    <svg
      className={classNames(className ?? '', 'fill-current')}
      width="16"
      height="16"
      viewBox="0 0 16 16"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path d="M9.79333 1.47334L2.88667 7.66001C2.46 8.04667 2.7 8.76001 3.27333 8.81334L8.66667 9.33334L5.43333 13.84C5.28666 14.0467 5.30666 14.3333 5.48666 14.5133C5.68666 14.7133 6 14.72 6.20667 14.5267L13.1133 8.34001C13.54 7.95334 13.3 7.24001 12.7267 7.18667L7.33333 6.66667L10.5667 2.16001C10.7133 1.95334 10.6933 1.66667 10.5133 1.48667C10.4191 1.39042 10.2908 1.33507 10.1561 1.33258C10.0214 1.33008 9.89109 1.38064 9.79333 1.47334Z" />
    </svg>
  );
}
