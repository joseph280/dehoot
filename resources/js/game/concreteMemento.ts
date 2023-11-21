/* eslint-disable import/no-cycle */
import { Memento } from './memory';
import { PlayerBuildings } from './scenes/main';

export class ConcreteMemento implements Memento {
  state: PlayerBuildings[];

  constructor(state: PlayerBuildings[]) {
    this.state = state;
  }

  public getState(): PlayerBuildings[] {
    return this.state;
  }
}
