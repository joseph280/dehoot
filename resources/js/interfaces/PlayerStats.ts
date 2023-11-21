import { ServiceConsumption } from './ServiceConsumption';
import { Token } from './Token';

export default interface PlayerStats {
  success: boolean;
  data: {
    hootBalance: Token;
    waxBalance: Token;
    processing: boolean;
    consumption: ServiceConsumption;
    population: number;
  };
  metadata: {};
}
