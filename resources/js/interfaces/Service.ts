import IAsset from './IAsset';

export type ServiceType = 'Water' | 'Energy' | 'Transport';

export default interface Service extends IAsset {
  type?: ServiceType;
  description?: string;
  capacity?: string;
  season?: string;
}
