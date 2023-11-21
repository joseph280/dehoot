import { Reward } from './Reward';

export default interface RewardResponse {
  success: boolean;
  data: {
    reward: Reward;
  };
  metadata: {};
}
