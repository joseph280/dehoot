import React from 'react';

interface IndicatorProps {
  children: React.ReactNode;
  icon: React.ReactNode;
}

export default function Indicator({ children, icon }: IndicatorProps) {
  return (
    <div className="relative w-full rounded-lg bg-gradient-to-r from-dehoot-purple-500 to-dehoot-blue-500 px-[0.8px] pt-[0.8px] pb-[2px] text-slate-50">
      <div className="justify-left flex items-center rounded-md bg-gray-800 py-[5px] px-1">
        {icon}
        {children}
      </div>
    </div>
  );
}
