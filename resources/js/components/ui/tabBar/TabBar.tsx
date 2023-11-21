import React from 'react';
import { SettingsIcon } from '@/components/icons/SettingsIcon';
import { RewardIcon } from '@/components/icons/RewardIcon';
import { BuildIcon } from '@/components/icons/BuildIcon';

interface TabBarProps {
  openSettings: () => void;
  openClaim: () => void;
  openBuild: () => void;
}

export default function TabBar({
  openSettings,
  openClaim,
  openBuild,
}: TabBarProps) {
  return (
    <div className="h-24 w-full max-w-md rounded-t-xl bg-slate-800 px-10 py-4 lg:px-16">
      <div className="flex h-full items-center justify-between">
        <div
          onClick={openSettings}
          className="flex w-20 cursor-pointer flex-col items-center justify-center space-y-1"
        >
          <SettingsIcon />
          <span className="select-none text-sm font-medium text-slate-200">
            Settings
          </span>
        </div>
        <div
          onClick={openClaim}
          className="flex h-[105px] w-[105px] -translate-y-8 cursor-pointer flex-col items-center justify-center space-y-1 rounded-full bg-gradient-to-r from-dehoot-blue-500 to-dehoot-purple-500"
        >
          <RewardIcon />
          <span className="select-none text-lg font-medium text-slate-200">
            Claim
          </span>
        </div>
        <div
          onClick={openBuild}
          className="flex w-20 cursor-pointer flex-col items-center justify-center space-y-1"
        >
          <BuildIcon />
          <span className="select-none text-sm font-medium text-slate-200">
            Build
          </span>
        </div>
      </div>
    </div>
  );
}
