import { AssetsMeta } from './AssetsMeta';
import Residential from './Residential';
import Service from './Service';
import SpecialBuild from './SpecialBuild';

export interface StakingAssets {
  residentialBuildings: Residential[];
  specialBuildings: SpecialBuild[];
  serviceBuildings: Service[];
}

export interface AssetsCollection {
  data: {
    residential: Residential[];
    specialBuild: SpecialBuild[];
    service: Service[];
    staking: StakingAssets;
  };
  meta: AssetsMeta;
}
