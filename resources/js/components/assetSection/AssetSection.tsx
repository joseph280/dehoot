import React from 'react';

interface SectionProps {
  title: string;
  children: React.ReactChild;
}

export function AssetSection({ title, children }: SectionProps) {
  return (
    <div>
      <h3 className="mb-4 text-2xl font-bold text-white">{title}</h3>
      <div>{children}</div>
    </div>
  );
}
