import axios from 'axios';
import useSWR from 'swr';
import PlayerAssets from '@/interfaces/PlayerAssets';

const fetcher = (url: string) => axios.get(url).then(response => response.data);

export function usePlayerAssets() {
  const {
    data: response,
    error,
    mutate,
  } = useSWR<PlayerAssets>('/api/v1/player/assets', fetcher, {
    refreshInterval: 3000,
  });

  return {
    data: response?.data,
    stakedAssets: response?.data.stakedAssets,
    metadata: response?.metadata,
    success: response?.success,
    revalidatePlayerAssets: mutate,
    error,
    loading: !response && !error,
  };
}
