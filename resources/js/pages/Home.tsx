import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/inertia-react';
import { Asset } from '@/types/schemas';
import { Layout } from '@/components/ui/layout/Layout';
import { useAppState } from '@/contexts/stateContext';
import { useApi } from '@/hooks/useApi';
import { usePlayerAssets } from '@/hooks/usePlayerAssets';
import { Game } from '@/game/game';
import { Alert, AlertTypes } from '@/components/ui/alert/Alert';

export default function Home() {
  const [created, setCreated] = useState<boolean>(false);
  const { stakedAssets, revalidatePlayerAssets } = usePlayerAssets();
  const { state, dispatch } = useAppState();
  const { stake } = useApi();

  useEffect(() => {
    if (state.game && stakedAssets) {
      state.game.syncStakedAssets(stakedAssets);
    }
  }, [stakedAssets, state.game]);

  useEffect(() => {
    if (!created && stakedAssets) {
      const game = new Game(stakedAssets);
      dispatch({ type: 'set', payload: { game } });
      setCreated(true);
    }
  }, [created, dispatch, stakedAssets, state.game]);

  useEffect(() => {
    if (state.game) {
      document.addEventListener('stakeAsset', (ev: Event) => {
        const event = ev as CustomEvent;
        stake(event.detail.asset as Asset)
          .then(response => {
            if (response.data.flash) {
              dispatch({
                type: 'set',
                payload: {
                  message: response.data.flash.message,
                  status: response.data.flash.status,
                  processing: false,
                },
              });
            }
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
            state.game?.undo('stake');
          })
          .finally(() =>
            revalidatePlayerAssets().then(() => state.observable?.notify(true)),
          );
      });
    }

    return () => document.removeEventListener('stakeAsset', () => {});
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [state.game]);

  return (
    <Layout>
      <Head title="Home" />
      {state.message && (
        <Alert
          message={state.message}
          status={state.status as AlertTypes}
          handleClose={() => dispatch({ type: 'reset' })}
        />
      )}
      <div id="phaser" />
    </Layout>
  );
}
