/* eslint-disable import/no-cycle */
import { PlayerBuildings } from './scenes/main';
import { ConcreteMemento } from './concreteMemento';

export interface Memento {
  getState(): PlayerBuildings[];
}

export class Memory {
  state: PlayerBuildings[];

  constructor(state: PlayerBuildings[]) {
    this.state = state;
  }

  public update(state: PlayerBuildings[]): void {
    this.state = state;
  }

  /**
   * Saves the current state inside a memento.
   */
  public save(): Memento {
    return new ConcreteMemento(this.state);
  }

  /**
   * Restores the Originator's state from a memento object.
   */
  public restore(memento: Memento): void {
    this.state = memento.getState();
  }
}
