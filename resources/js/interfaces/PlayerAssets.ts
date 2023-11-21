import { Asset } from '@/types/schemas';
import Residential from './Residential';
import Service from './Service';
import SpecialBuild from './SpecialBuild';

export type StakedAssets = {
  residentialBuildings: Residential[];
  specialBuildings: SpecialBuild[];
  serviceBuildings: Service[];
};

export default interface PlayerAssets {
  success: boolean;
  data: {
    assets: Asset[];
    stakedAssets: StakedAssets;
    unstakedAssets: {
      residentialBuildings: Residential[];
      specialBuildings: SpecialBuild[];
      serviceBuildings: Service[];
    };
    stakingLimit: number;
  };
  metadata: {
    residentialBuildingsCount: number;
    serviceBuildingsCount: number;
    specialBuildingsCount: number;
  };
}
