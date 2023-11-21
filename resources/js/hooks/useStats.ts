import axios from 'axios';
import useSWR from 'swr';
import Stat from '@/interfaces/PlayerStats';

const fetcher = (url: string) => axios.get(url).then(response => response.data);

export function useStats() {
  const {
    data: response,
    error,
    mutate,
  } = useSWR<Stat>('api/v1/player/stats', fetcher, {
    refreshInterval: 5000,
  });

  return {
    success: response?.success,
    stats: response?.data,
    consumption: response?.data.consumption,
    population: response?.data.population,
    transactionProcessing: Boolean(response?.data.processing),
    revalidateStats: mutate,
    error,
    loading: !response && !error,
  };
}
