import React from 'react';
import { AssetSchema } from '@/types/schemas';

interface AssetMessageDisplayProps {
  type: AssetSchema;
  count: number;
  length: number;
}

export function AssetMessageDisplay({
  type,
  count,
  length,
}: AssetMessageDisplayProps) {
  if (count === 0) {
    return (
      <div className="h-[450px]">
        <p className="mt-10 px-4 text-center text-lg font-light text-white">
          {type === 'residential' && <>You don't own any residentials to stake!</>}
          {type === 'service' && <>You don't own any services to stake!</>}
          {type === 'specialbuild' && <>You don't own any specials buildings to stake!</>}
        </p>
      </div>
    );
  }

  if (length === 0) {
    return (
      <div className="h-[450px]">
        <p className="mt-10 px-4 text-center text-lg font-light text-white">
          {type === 'residential' && <>All your residentials are in staking!</>}
          {type === 'service' && <>All your services are in staking!</>}
          {type === 'specialbuild' && <>All your special buildings are in staking!</>}
        </p>
      </div>
    );
  }

  return (
    <div className="h-[450px]">
      <p className="mt-10 px-4 text-center text-lg font-light text-white">
        Loading
      </p>
    </div>
  );
}
