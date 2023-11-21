import Service from '@/interfaces/Service';
import IAsset from '../interfaces/IAsset';
import Residential from '../interfaces/Residential';
import SpecialBuild from '../interfaces/SpecialBuild';

export type Asset = IAsset | Residential | SpecialBuild | Service;

export type AssetSchema =
  | 'residential'
  | 'specialbuild'
  | 'service';
