import axios from 'axios';
import useSWR from 'swr';
import RewardResponse from '@/interfaces/RewardResponse';

const fetcher = (url: string) => axios.get(url).then(response => response.data);

export function useReward() {
  const {
    data: response,
    error,
    mutate,
  } = useSWR<RewardResponse>('api/v1/player/reward', fetcher, {
    refreshInterval: 10000,
  });

  return {
    success: response?.success,
    reward: response?.data.reward,
    revalidateReward: mutate,
    error,
    loading: !response && !error,
  };
}
