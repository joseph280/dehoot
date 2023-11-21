import React from 'react';
import { useStats } from '@/hooks/useStats';
import Indicator from '../indicator/Indicator';
import { CityEnergyIcon } from '@/components/icons/CityEnergyIcon';
import { CityPopulationIcon } from '@/components/icons/CityPopulationIcon';
import { CityWaterIcon } from '@/components/icons/CityWaterIcon';
import { classNames } from '@/support/Global';

export function Navbar() {
  const { consumption, population, stats } = useStats();

  return (
    <div className="absolute top-0 z-10 mt-5 flex w-full justify-center px-4 font-Poppins">
      <div
        className={classNames(
          !stats ? 'animate-pulse' : '',
          'w-full max-w-2xl',
        )}
      >
        <div className="mb-3 grid w-full grid-cols-2 gap-2">
          <Indicator
            icon={
              <img
                className="mr-1 h-6"
                src="/assets/icons/hoot-coin.png"
                alt="Hoot coin"
              />
            }
          >
            <p className="align-bottom font-bold">
              {stats?.hootBalance.formattedShorten}{' '}
              <span className="text-[10px] font-bold">HOOT</span>
            </p>
          </Indicator>
          <Indicator
            icon={
              <img
                className="mr-1 h-6"
                src="/assets/icons/wax-coin.png"
                alt="Hoot coin"
              />
            }
          >
            <p className="align-bottom font-bold">
              {stats?.waxBalance.formattedShorten}{' '}
              <span className="text-[10px] font-bold">WAX</span>
            </p>
          </Indicator>
        </div>
        <div className="grid w-full grid-cols-3 gap-2">
          <Indicator icon={<CityEnergyIcon className="mr-1" />}>
            <p className="inline-flex h-6 items-end text-sm font-bold">
              {consumption && (
                <>
                  {consumption?.energy.current} / {consumption?.energy.total}
                </>
              )}
            </p>
          </Indicator>
          <Indicator icon={<CityWaterIcon className="mr-1" />}>
            <p className="inline-flex h-6 items-end text-sm font-bold">
              {consumption && (
                <>
                  {consumption?.water.current} / {consumption?.water.total}
                </>
              )}
            </p>
          </Indicator>
          <Indicator icon={<CityPopulationIcon className="mr-1" />}>
            <p className="inline-flex h-6 items-end text-sm font-bold">
              {population}
            </p>
          </Indicator>
        </div>
      </div>
    </div>
  );
}
