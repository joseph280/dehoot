import React, { useEffect, useState } from 'react';
import { AnimatePresence } from 'framer-motion';
import { RewardIndicator } from '@/components/reward/RewardIndicator';
import TabBar from '../tabBar/TabBar';
import BuildSelection from '../build/BuildSelection';
import BuildResidentials from '../build/BuildResidentials';
import { Asset, AssetSchema } from '@/types/schemas';
import Residential from '@/interfaces/Residential';
import { useAppState } from '@/contexts/stateContext';
import { RaisedButton } from '../button/RaisedButton';
import { usePlayerAssets } from '@/hooks/usePlayerAssets';
import { ClaimRewardModal } from '@/components/modals/ClaimRewardModal';
import { useReward } from '@/hooks/useReward';
import SettingsModal from '@/components/modals/SettingsModal';
import BuildSpecials from '../build/BuildSpecials';
import SpecialBuild from '@/interfaces/SpecialBuild';
import BuildServices from '../build/BuildServices';
import Service from '@/interfaces/Service';

export default function Footer() {
  const [openBuildModal, setOpenBuildModal] = useState<boolean>(false);
  const [openClaimModal, setOpenClaimModal] = useState<boolean>(false);
  const [openSettingsModal, setOpenSettingModal] = useState<boolean>(false);
  const [openResidentialModal, setOpenResidentialModal] = useState<boolean>(false);
  const [openSpecialModal, setOpenSpecialModal] = useState<boolean>(false);
  const [openServiceModal, setOpenServiceModal] = useState<boolean>(false);
  const [canBuild, setCanBuild] = useState<boolean>(true);
  const { reward } = useReward();
  const { state, dispatch } = useAppState();
  const { data, metadata } = usePlayerAssets();
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (state.observable) {
      state.observable.subscribe(() => setLoading(false));
    }
  }, [state.observable]);

  useEffect(() => {
    document.addEventListener('playerCanBuild', (ev: Event) => {
      const event = ev as CustomEvent;
      setCanBuild(event.detail.playerCanBuild as boolean);
    });

    return () => document.removeEventListener('userCanBuild', () => {});
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const openBuildTypeModal = (type: AssetSchema) => {
    if (type === 'residential') {
      setOpenResidentialModal(true);
    }

    if (type === 'specialbuild') {
      setOpenSpecialModal(true);
    }

    if (type === 'service') {
      setOpenServiceModal(true);
    }
  };

  const handleAssetSelection = (asset: Asset) => {
    setOpenBuildModal(false);
    setOpenResidentialModal(false);
    setOpenSpecialModal(false);
    setOpenServiceModal(false);
    state.game?.setBuildMode('build');
    state.game?.setAsset(asset);
  };

  return (
    <div className="absolute bottom-0 z-10 flex w-full justify-center font-Poppins">
      {state.game?.mode === 'view' && (
        <>
          <RewardIndicator />
          <TabBar
            openBuild={() => setOpenBuildModal(true)}
            openClaim={() => setOpenClaimModal(true)}
            openSettings={() => setOpenSettingModal(true)}
          />
        </>
      )}
      {state.game?.mode === 'build' && (
        <div className="flex w-full max-w-lg justify-between space-x-9 px-3 pb-6 text-xl">
          <RaisedButton
            className="border-red-900 bg-red-700 font-bold text-slate-50"
            onClick={() => {
              state.game?.abortStake();
              dispatch({ type: 'set', payload: { game: state.game } });
            }}
          >
            Cancel
          </RaisedButton>
          <RaisedButton
            className="border-green-900 bg-green-700 font-bold text-slate-50"
            disabled={!canBuild}
            onClick={() => {
              state.game?.stake();
              dispatch({ type: 'set', payload: { game: state.game } });
              setLoading(true);
            }}
          >
            Accept
          </RaisedButton>
        </div>
      )}
      <AnimatePresence>
        {openSettingsModal && (
          <SettingsModal
            key="SettingsModal"
            onClose={() => setOpenSettingModal(false)}
          />
        )}
        {openBuildModal && (
          <BuildSelection
            key="BuildModal"
            openMenu={openBuildTypeModal}
            onClose={() => setOpenBuildModal(false)}
          />
        )}
        {openClaimModal && reward && reward.total.value > 0 && (
          <ClaimRewardModal
            key="ClaimModal"
            reward={reward}
            onClose={() => setOpenClaimModal(false)}
          />
        )}
        {openResidentialModal && data && metadata && !loading && (
          <BuildResidentials
            key="BuildResidentialsModal"
            stakedAssets={data.stakedAssets.residentialBuildings}
            stakingLimit={data.stakingLimit}
            unstakedAssets={data.unstakedAssets.residentialBuildings}
            count={metadata.residentialBuildingsCount}
            onClose={() => setOpenResidentialModal(false)}
            onCloseWithAsset={(asset: Residential) => handleAssetSelection(asset)}
          />
        )}
        {openServiceModal && data && metadata && !loading && (
          <BuildServices
            key="BuildServicesModal"
            unstakedAssets={data.unstakedAssets.serviceBuildings}
            count={metadata.serviceBuildingsCount}
            onClose={() => setOpenServiceModal(false)}
            onCloseWithAsset={(asset: Service) => handleAssetSelection(asset)}
          />
        )}
        {openSpecialModal && data && metadata && !loading && (
          <BuildSpecials
            key="BuildSpecialsModal"
            unstakedAssets={data.unstakedAssets.specialBuildings}
            count={metadata.specialBuildingsCount}
            onClose={() => setOpenSpecialModal(false)}
            onCloseWithAsset={(asset: SpecialBuild) => handleAssetSelection(asset)}
          />
        )}
      </AnimatePresence>
    </div>
  );
}
