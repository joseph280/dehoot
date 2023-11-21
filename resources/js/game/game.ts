import Phaser from 'phaser';
import GesturesPlugin from 'phaser3-rex-plugins/plugins/gestures-plugin.js';
import { Asset } from '@/types/schemas';
import { Main, UndoActionType } from './scenes/main';
import { StakedAssets } from '@/interfaces/PlayerAssets';

export class Game {
  game: Phaser.Game;

  mode: string;

  asset?: Asset;

  stakedAssets!: StakedAssets;

  constructor(stakedAssets: StakedAssets) {
    this.game = new Phaser.Game({
      type: Phaser.AUTO,
      backgroundColor: '#90B448',
      scene: [Main],
      scale: {
        mode: Phaser.Scale.FIT,
        parent: 'phaser',
        autoCenter: Phaser.Scale.CENTER_BOTH,
        width: window.innerWidth,
        height: window.innerHeight,
      },
      plugins: {
        scene: [
          {
            key: 'rexGestures',
            plugin: GesturesPlugin,
            mapping: 'rexGestures',
          },
        ],
      },
    });
    this.mode = 'view';
    this.stakedAssets = stakedAssets;
    this.game.registry.set('mode', this.mode);
    this.game.registry.set('stakedAssets', this.stakedAssets);
  }

  syncStakedAssets(stakedAssets: StakedAssets) {
    this.stakedAssets = stakedAssets;
    const scene = this.game.scene.getScene('Main');
    if (scene) {
      scene.events.emit('syncStakedAssets', stakedAssets);
    }
  }

  unstake(asset: Asset) {
    this.mode = 'view';
    this.game.registry.set('mode', 'view');
    this.game.scene.getScene('Main').events.emit('unstake', asset);
  }

  stake() {
    this.mode = 'view';
    this.game.registry.set('mode', 'view');
    this.game.scene.getScene('Main').events.emit('stake', this.asset);
  }

  abortStake() {
    this.mode = 'view';
    this.game.registry.set('mode', 'view');
    this.game.scene.getScene('Main').events.emit('abort-stake');
  }

  setBuildMode(mode: string) {
    this.mode = mode;
    this.game.registry.set('mode', mode);
  }

  setAsset(asset: Asset) {
    this.asset = asset;
    this.game.registry.set('asset', this.asset);
  }

  undo(type: UndoActionType) {
    this.game.scene.getScene('Main').events.emit('undo', type);
  }
}
