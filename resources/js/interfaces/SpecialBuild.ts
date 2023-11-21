import IAsset from './IAsset';

export default interface SpecialBuild extends IAsset {
  description?: string;
  type?: string;
  season?: string;
}
