import IAsset from './IAsset';

type ResidentialType = 'Houser' | 'Building';

export default interface Residential extends IAsset {
  description?: string;
  level?: string;
  type?: ResidentialType;
  population?: string;
  water?: string;
  energy?: string;
  season?: string;
}
