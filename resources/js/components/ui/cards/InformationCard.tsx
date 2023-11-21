import React from 'react';

interface InformationCardProps {
  className?: string;
  title: string;
  message: string;
}

export function InformationCard({
  className,
  title,
  message,
}: InformationCardProps) {
  return (
    <div
      className={
        className ??
        'flex w-full items-center justify-center rounded-lg border border-gray-600 bg-gray-800 py-16 text-slate-50 shadow-inner'
      }
    >
      <div className="text-center">
        <h1 className="text-xl font-bold">{title}</h1>
        <span className="text-base font-normal">{message}</span>
      </div>
    </div>
  );
}
