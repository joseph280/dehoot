import React from 'react';
import { Modal } from '@/components/ui/modal/Modal';
import { Button } from '@/components/ui/button/Button';
import { XButton } from '@/components/ui/button/XButton';
import { Reward } from '@/interfaces/Reward';
import { classNames } from '@/support/Global';
import { useApi } from '@/hooks/useApi';
import { useStats } from '@/hooks/useStats';
import { useAppState } from '@/contexts/stateContext';

interface ClaimRewardModalProps {
  reward: Reward;
  onClose: () => void;
}

export function ClaimRewardModal({ reward, onClose }: ClaimRewardModalProps) {
  const { claimAll } = useApi();
  const { transactionProcessing, revalidateStats, stats } = useStats();
  const { state } = useAppState();

  const claim = async () => {
    await claimAll();

    revalidateStats({
      success: true,
      metadata: {},
      data: {
        ...stats!,
        processing: true,
      }
    });
  };

  return (
    <Modal
      position="justify-center"
      padding="p-0"
      bgOpacity="bg-opacity-50"
      onClose={() => onClose()}
    >
      <div className="relative mt-12 flex w-full max-w-sm scale-90 flex-col rounded-xl border border-b-4 border-slate-50 bg-slate-900 bg-opacity-75 px-6 py-11 text-center shadow-xl drop-shadow-md md:mt-4 md:scale-100">
        <div className="absolute inset-x-0 top-0 inline-flex -translate-y-10 justify-center">
          <XButton onClick={onClose} />
        </div>
        <h1 className="mb-4 text-2xl font-bold text-slate-50">City Reward</h1>
        <p className="mb-12 text-center text-slate-50">
          Your city has generated
        </p>

        <div className="mb-9">
          <div className="mb-2 flex justify-between">
            <span className="text-left text-slate-50">Staked</span>
            <span className="text-right font-bold text-slate-50">
              + {reward.staked.formatted} Hoots
            </span>
          </div>
          {reward.effects?.map(effect => (
            <div key={effect.name} className="mb-2 flex justify-between">
              <span className="text-left text-slate-50">{effect.name}</span>
              <span className="text-right font-bold text-slate-50">
                + {effect.bonus.formatted} Hoots
              </span>
            </div>
          ))}
          <div
            className={classNames(
              reward.bonus.value === 0 ? 'hidden' : 'flex',
              'mb-2 justify-between',
            )}
          >
            <span className="text-left text-slate-50">VIP Bonus</span>
            <span className="text-right font-bold text-slate-50">
              + {reward.bonus.formatted} Hoots
            </span>
          </div>
          <div className="mb-4 flex justify-between">
            <span className="text-left text-slate-50">Tax (5%)</span>
            <span className="text-right font-bold text-slate-50">
              - {reward.tax.formatted} Hoots
            </span>
          </div>
          <div className="mb-2 h-1 w-full rounded-full bg-slate-50" />
          <div className="flex justify-between font-bold">
            <span className="text-left text-slate-50">Total</span>
            <span className="text-right text-amber-400">
              {reward.total.formatted} Hoots
            </span>
          </div>
        </div>
        <Button
          className="border-amber-700 bg-amber-500 text-white active:bg-amber-700"
          processing={transactionProcessing || state.processing}
          text="Claim"
          onClick={() => claim()}
        />
      </div>
    </Modal>
  );
}
