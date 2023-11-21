import { Token } from './Token';

export default interface IAsset {
  assetId: string;
  templateId: string;
  schema: string;
  owner: string;
  imgUrl: string;
  name: string;
  staking: boolean;
  stakedBalance: Token;
  position_x: number;
  position_y: number;
  rows: number;
  columns: number;
}
