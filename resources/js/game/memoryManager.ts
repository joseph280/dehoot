/* eslint-disable import/no-cycle */
import { Memento, Memory } from './memory';

export class MemoryManager {
  mementos: Memento[] = [];

  private memory: Memory;

  constructor(memory: Memory) {
    this.memory = memory;
  }

  public update(state: any): void {
    this.memory.update(state);
  }

  public backup(): void {
    this.mementos.push(this.memory.save());
  }

  public undo(): void {

    const memento = this.mementos.pop();
    
    if (memento) {
      this.memory.restore(memento);
    }
  }
}
