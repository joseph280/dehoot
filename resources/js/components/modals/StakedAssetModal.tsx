import React from 'react';
import { Asset } from '@/types/schemas';
import { ResidentialModal } from './ResidentialModal';
import Residential from '@/interfaces/Residential';
import { SpecialBuildModal } from './SpecialBuildModal';
import SpecialBuild from '@/interfaces/SpecialBuild';
import { ServiceModal } from './ServiceModal';
import Service, { ServiceType } from '@/interfaces/Service';
import { useAppState } from '@/contexts/stateContext';
import { useApi } from '@/hooks/useApi';
import { useStats } from '@/hooks/useStats';
import { usePlayerAssets } from '@/hooks/usePlayerAssets';

interface StakedAssetModalProps {
  onClose: () => void;
  stakedAsset: Asset;
}

export function StakedAssetModal({
  stakedAsset,
  onClose,
}: StakedAssetModalProps) {
  const { unstake } = useApi();
  const { state, dispatch } = useAppState();
  const { revalidatePlayerAssets } = usePlayerAssets();
  const { stats, revalidateStats } = useStats();

  const unstakeAsset = async (asset: Asset) => {
    state.game?.unstake(asset);
    await unstake(asset)
      .then(response => {
        dispatch({
          type: 'set',
          payload: {
            message: response.data.flash.message,
            status: response.data.flash.status,
            processing: false,
          },
        });
        revalidateStats({
          success: true,
          metadata: {},
          data: {
            ...stats!,
            processing: true,
          },
        });
        revalidatePlayerAssets();
      })
      .catch((error: any) => {
        dispatch({
          type: 'set',
          payload: {
            message: error.response.data.flash.message,
            status: error.response.data.flash.status,
            processing: false,
          },
        });
        state.game?.undo('unstake');
      });
  };

  if (stakedAsset.schema === 'residential') {
    return (
      <ResidentialModal
        onClose={onClose}
        unstakeOnClose={unstakeAsset}
        asset={stakedAsset as Residential}
      />
    );
  }

  if (stakedAsset.schema === 'specialbuild') {
    return (
      <SpecialBuildModal
        onClose={onClose}
        unstakeOnClose={unstakeAsset}
        asset={stakedAsset as SpecialBuild}
      />
    );
  }

  if (stakedAsset.schema === 'service') {
    const service = stakedAsset as Service;

    return (
      <ServiceModal
        onClose={onClose}
        unstakeOnClose={unstakeAsset}
        asset={service}
        type={service.type as ServiceType}
      />
    );
  }

  return <div />;
}
