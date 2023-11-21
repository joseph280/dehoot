import axios from 'axios';
import { Asset } from '@/types/schemas';
import { useAppState } from '@/contexts/stateContext';

export function useApi() {
  const { dispatch } = useAppState();

  const stake = async (asset: Asset) => {
    dispatch({ type: 'processing' });
    return axios.post('api/v1/stake', {
      asset_id: asset.assetId,
      template_id: asset.templateId,
      land: '1',
      position_x: asset.position_x,
      position_y: asset.position_y,
    });
  };

  const unstake = async (asset: Asset) => {
    dispatch({ type: 'processing' });
    return axios.post('api/v1/unstake', {
      asset_id: asset.assetId,
    });
  };

  const claimAll = async () => {
    dispatch({ type: 'processing' });
    return axios.post('api/v1/claim-all');
  };

  return { stake, unstake, claimAll };
}
