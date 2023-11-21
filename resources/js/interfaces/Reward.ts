import { Asset } from '@/types/schemas';
import { EffectValue } from './EffectValue';
import { Token } from './Token';

export interface Reward {
  staked: Token;
  bonus: Token;
  tax: Token;
  total: Token;
  stakedWithBonus: Token;
  stakedAssets: Asset[];
  effects?: EffectValue[]; 
}
