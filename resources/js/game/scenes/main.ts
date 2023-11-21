/* eslint-disable no-plusplus */
/* eslint-disable import/no-cycle */
import Phaser from 'phaser';
import Pinch from 'phaser3-rex-plugins/plugins/input/gestures/pinch/Pinch';
import { clamp, generateMinAndMaxZoom } from '@/support/Global';
import { Asset } from '@/types/schemas';
import { StakedAssets } from '@/interfaces/PlayerAssets';
import { Memory } from '../memory';
import { MemoryManager } from '../memoryManager';

export interface PlayerBuildings {
  image: Phaser.GameObjects.Image;
  tiles: Phaser.Tilemaps.Tile[];
  stakedAsset: Asset;
}

export interface ZoomLimits {
  minZoom: number;
  maxZoom: number;
}

export type UndoActionType = 'unstake' | 'stake';

export type GameMode = 'view' | 'build';

export class Main extends Phaser.Scene {
  controls!: Phaser.Cameras.Controls.SmoothedKeyControl;

  map!: Phaser.Tilemaps.Tilemap;

  baseLayer!: Phaser.Tilemaps.TilemapLayer;

  selector!: Phaser.GameObjects.Image;

  tile!: Phaser.Tilemaps.Tile;

  tilesOffset: number = 6;

  playerBuildings: PlayerBuildings[] = [];

  memory!: Memory;

  manager!: MemoryManager;

  stakedAssets: Asset[] = [];

  playerCanBuild: boolean = false;

  constructor() {
    super('Main');
  }

  preload() {
    this.load.image('selector', 'assets/assets/selector.png');
    this.load.image('base', 'assets/assets/terrain/1/base.png');
    this.load.image('baseTrees', 'assets/assets/terrain/1/baseTrees.png');

    this.load.image('mountainI', 'assets/assets/terrain/1/mountain-I.png');
    this.load.image('mountainII', 'assets/assets/terrain/1/mountain-II.png');
    this.load.image('mountainIII', 'assets/assets/terrain/1/mountain-III.png');
    this.load.image('mountainIV', 'assets/assets/terrain/1/mountain-IV.png');

    this.load.tilemapTiledJSON('map', 'assets/tilemaps/DehootMap.json');
  }

  create() {
    this.add.image(-2380, 3000, 'mountainI').setDepth(1).setScale(3, 3);
    this.add.image(2500, 6400, 'mountainII').setDepth(1).setScale(3, 3);
    this.add.image(-1900, 6400, 'mountainIII').setDepth(1).setScale(3, 3);
    this.add.image(1400, 2500, 'mountainIV').setDepth(1).setScale(3, 3);

    this.selector = this.add
      .image(256, 4914, 'selector')
      .setDepth(20)
      .setVisible(false)
      .setInteractive();

    this.map = this.add.tilemap('map');

    this.map.addTilesetImage('base', 'base');
    this.map.addTilesetImage('baseTrees', 'baseTrees');

    this.baseLayer = this.map.createLayer('Base', ['base']);
    this.map.createLayer('Limits', ['base', 'baseTrees']);

    const zooms = generateMinAndMaxZoom();
    const zoom = (zooms.minZoom + zooms.maxZoom) / 2;

    const camera = this.cameras.main;
    camera.setZoom(zoom);
    camera.setBounds(-2800, 2500, 6100, 4400);
    camera.centerToBounds();

    this.tile = this.baseLayer.getTileAt(13, 13);

    const cursors = this.input.keyboard.createCursorKeys();
    this.controls = new Phaser.Cameras.Controls.SmoothedKeyControl({
      camera: this.cameras.main,
      left: cursors.left,
      right: cursors.right,
      up: cursors.up,
      down: cursors.down,
      acceleration: 0.04,
      drag: 0.0005,
      maxSpeed: 0.7,
      zoomIn: this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.Q),
      zoomOut: this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.E),
      maxZoom: zooms.maxZoom,
      minZoom: zooms.minZoom,
    });

    const pinchManager = new Pinch(this, {
      enable: true,
      bounds: undefined,
      threshold: 0,
    });

    pinchManager.on('drag1', this.dragCamera, this).on('pinch', this.pinchCamera, this);
    this.input.on('wheel', this.zoomByWheelScroll, this);
    this.input.on(Phaser.Input.Events.POINTER_UP, this.selectStakedAsset, this);
    this.input.on(Phaser.Input.Events.POINTER_UP, this.moveSelector, this);
    this.events.on('syncStakedAssets', this.syncStakedAssets, this);
    this.events.on('stake', this.stake, this);
    this.events.on('abort-stake', this.abortStake, this);
    this.events.on('unstake', this.unstake, this);
    this.events.on('undo', this.undo, this);

    const stakedAssets = this.registry.get('stakedAssets') as StakedAssets;

    if (stakedAssets) {
      this.initializeStakedAssetsOnMap(stakedAssets);
      this.memory = new Memory([...this.playerBuildings]);
      this.manager = new MemoryManager(this.memory);
    }
  }

  dynamicLoadImage(
    textureName: string,
    textureUrl: string,
    worldPosition: Phaser.Math.Vector2,
    depth: number,
  ) {
    let image: Phaser.GameObjects.Image;

    if (this.textures.exists(textureName)) {
      image = this.add
        .image(worldPosition.x, worldPosition.y, textureName)
        .setDepth(depth);

      image.y -= (image.height - 364) / 2;
    } else {
      this.load.image(textureName, textureUrl);

      this.load.start();

      image = this.add
        .image(worldPosition.x, worldPosition.y, textureName)
        .setDepth(depth);

      this.load.once(Phaser.Loader.Events.COMPLETE, () => {
        image.setTexture(textureName);
        image.y -= (image.height - 364) / 2;
      });
    }
    return image;
  }

  generateStakedAssetsOnTerrain(stakedAssets: Asset[]) {
    stakedAssets.forEach(stakedAsset => {
      const tileX = stakedAsset.position_x + this.tilesOffset;
      const tileY = stakedAsset.position_y + this.tilesOffset;
      const tiles: Phaser.Tilemaps.Tile[] = [];

      for (let rowIndex = 0; rowIndex < stakedAsset.rows; rowIndex++) {
        for (let columnIndex = 0; columnIndex < stakedAsset.columns; columnIndex++) {
          tiles.push(this.baseLayer.getTileAt(tileX - rowIndex, tileY - columnIndex));
        }
      }

      let depth = stakedAsset.position_x + stakedAsset.position_y - 1;

      if (stakedAsset.rows > 1 || stakedAsset.columns > 1) {
        depth -= 1;
      }

      const position = this.baseLayer.tileToWorldXY(
        tileX + 1,
        tileY,
        undefined,
        this.cameras.main,
      );
      const image = this.dynamicLoadImage(
        stakedAsset.name,
        `assets/assets/${stakedAsset.schema}/${stakedAsset.imgUrl}.png`,
        position,
        depth,
      );

      this.playerBuildings.push({
        tiles,
        image,
        stakedAsset,
      });
    });
  }

  initializeStakedAssetsOnMap(stakedAssets: StakedAssets) {
    this.stakedAssets = this.stakedAssets
      .concat(stakedAssets.residentialBuildings)
      .concat(stakedAssets.serviceBuildings)
      .concat(stakedAssets.specialBuildings);

    this.generateStakedAssetsOnTerrain(this.stakedAssets);
  }

  syncStakedAssets(stakedAssets: StakedAssets) {
    let updatedStakedAssets: Asset[] = [];
    updatedStakedAssets = updatedStakedAssets
      .concat(stakedAssets.residentialBuildings)
      .concat(stakedAssets.serviceBuildings)
      .concat(stakedAssets.specialBuildings);

    this.playerBuildings.forEach(item => item.image.destroy());
    this.playerBuildings = [];

    this.generateStakedAssetsOnTerrain(updatedStakedAssets);

    this.stakedAssets = updatedStakedAssets;
  }

  stake(assetToStake: Asset) {
    this.selector.visible = false;

    if (!this.playerCanBuild) {
      return;
    }

    const tile = this.baseLayer.tileToWorldXY(
      this.tile.x + 1,
      this.tile.y,
      undefined,
      this.cameras.main,
    );

    const position = new Phaser.Math.Vector2(tile.x, tile.y);

    const positionX = this.tile.x - this.tilesOffset;
    const positionY = this.tile.y - this.tilesOffset;

    let depth = positionX + positionY - 1;

    if (assetToStake.rows > 1 || assetToStake.columns > 1) {
      depth -= 1;
    }

    const image = this.dynamicLoadImage(
      assetToStake.name,
      `assets/assets/${assetToStake.schema}/${assetToStake.imgUrl}.png`,
      position,
      depth,
    );

    const asset = assetToStake;

    asset.position_x = positionX;
    asset.position_y = positionY;

    const event = new CustomEvent('stakeAsset', {
      detail: {
        asset,
      },
    });
    document.dispatchEvent(event);

    this.manager.backup();

    const tiles: Phaser.Tilemaps.Tile[] = [];

    for (let rowIndex = 0; rowIndex < assetToStake.rows; rowIndex++) {
      for (let columnIndex = 0; columnIndex < assetToStake.columns; columnIndex++) {
        tiles.push(
          this.baseLayer.getTileAt(this.tile.x - rowIndex, this.tile.y - columnIndex),
        );
      }
    }

    this.playerBuildings.push({
      tiles,
      image,
      stakedAsset: asset,
    });

    this.manager.update([...this.playerBuildings]);
  }

  unstake(assetToUnstake: Asset) {
    const index = this.playerBuildings.findIndex(
      asset => asset.stakedAsset.assetId === assetToUnstake.assetId,
    );

    this.manager.backup();
    this.playerBuildings[index].image.destroy();
    this.playerBuildings.splice(index, 1);
    this.manager.update([...this.playerBuildings]);
  }

  abortStake() {
    this.selector.visible = false;
  }

  selectStakedAsset(pointer: Phaser.Input.Pointer) {
    const mode = this.registry.get('mode');
    const isViewMode = mode === 'view';

    if (!isViewMode) return;

    const { worldX, worldY } = pointer;

    const tile = this.baseLayer?.getTileAtWorldXY(
      worldX - Number(this.map?.tileWidth) / 2,
      worldY,
    ) as Phaser.Tilemaps.Tile;

    const stakedAsset = this.playerBuildings.find(item =>
      item.tiles.find(assetTile => assetTile.x === tile.x && assetTile.y === tile.y),
    )?.stakedAsset;

    if (stakedAsset) {
      const event = new CustomEvent('selectStakedAsset', {
        detail: {
          asset: stakedAsset,
        },
      });
      document.dispatchEvent(event);
    }
  }

  undo(type: UndoActionType) {
    if (type === 'stake') {
      this.manager.undo();
      const prevBuildings = [...this.memory.state];

      this.playerBuildings.forEach(item => item.image.destroy());

      prevBuildings.forEach(item =>
        this.add
          .image(item.image.x, item.image.y, item.image.texture)
          .setDepth(item.image.depth),
      );

      this.playerBuildings = [...this.memory.state];
    }

    if (type === 'unstake') {
      this.manager.undo();
      const prevBuildings = [...this.memory.state];

      const missingBuildings = prevBuildings.filter(
        item => !this.playerBuildings.includes(item),
      );

      if (missingBuildings && missingBuildings.length > 0) {
        missingBuildings.forEach(item =>
          this.add
            .image(item.image.x, item.image.y, item.image.texture)
            .setDepth(item.image.depth),
        );
      }

      this.playerBuildings = [...this.memory.state];
    }
  }

  moveSelector(pointer: Phaser.Input.Pointer) {
    const mode = this.registry.get('mode') as GameMode;

    if (mode === 'view') {
      return;
    }

    if (mode === 'build') {
      const { worldX, worldY } = pointer;

      const tile = this.baseLayer?.getTileAtWorldXY(
        worldX - Number(this.map?.tileWidth) / 2,
        worldY,
      ) as Phaser.Tilemaps.Tile;

      this.tile = tile;
    }
  }

  pinchCamera(pinch: any) {
    const { scaleFactor } = pinch;

    const newZoom = scaleFactor * this.cameras.main.zoom;

    this.cameras.main.zoom = clamp(
      newZoom,
      Number(this.controls?.minZoom),
      Number(this.controls?.maxZoom),
    );
  }

  dragCamera(pinch: any) {
    const { drag1Vector } = pinch;
    this.cameras.main.scrollX -= drag1Vector.x / this.cameras.main.zoom;
    this.cameras.main.scrollY -= drag1Vector.y / this.cameras.main.zoom;
  }

  zoomByWheelScroll(pointer: Phaser.Input.Pointer) {
    let newZoom = 0;
    const scaleFactor = 0.02;

    if (pointer.deltaY > 0) {
      newZoom = this.cameras.main.zoom - scaleFactor;
    }

    if (pointer.deltaY < 0) {
      newZoom = this.cameras.main.zoom + scaleFactor;
    }

    this.cameras.main.zoom = clamp(
      newZoom,
      Number(this.controls?.minZoom),
      Number(this.controls?.maxZoom),
    );
  }

  moveSelectorImageOnTiles(asset: Asset) {
    this.selector.scaleX = asset.columns;
    this.selector.scaleY = asset.rows;

    let isTileInUse = false;

    for (let rowIndex = 0; rowIndex < asset.rows; rowIndex++) {
      for (let columnIndex = 0; columnIndex < asset.columns; columnIndex++) {
        const activeTile = this.baseLayer.getTileAt(
          this.tile.x - rowIndex,
          this.tile.y - columnIndex,
        );

        isTileInUse = this.playerBuildings.some(building =>
          building.tiles.includes(activeTile),
        );
        if (isTileInUse) break;
      }
      if (isTileInUse) break;
    }

    this.playerCanBuild = !isTileInUse;
    this.selector.tint = isTileInUse ? 0xff0000 : 0xffffff;

    const xMin = 7 + asset.columns - 1;
    const xMax = 18;
    const yMin = 7 + asset.rows - 1;
    const yMax = 18;

    if (
      this.tile.x <= xMax &&
      this.tile.x >= xMin &&
      this.tile.y <= yMax &&
      this.tile.y >= yMin
    ) {
      this.selector.x = this.tile.pixelX + Number(this.map?.tileWidth) / 2;

      if (asset.rows > 1 || asset.columns > 1) {
        this.selector.y = this.tile.pixelY;
      } else {
        this.selector.y = this.tile.pixelY + Number(this.map?.tileHeight) / 2;
      }
    }

    const event = new CustomEvent('playerCanBuild', {
      detail: { playerCanBuild: this.playerCanBuild },
    });
    document.dispatchEvent(event);
  }

  update(time: number, delta: number) {
    if (this.controls) {
      this.controls.update(delta);
    }

    const mode = this.registry.get('mode') as GameMode;
    const asset = this.registry.get('asset') as Asset;
    this.selector.visible = mode === 'build';

    if (mode && mode === 'build' && asset) {
      this.moveSelectorImageOnTiles(asset);
    }
  }
}
