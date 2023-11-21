export interface Consumption {
  current: string;
  total: string;
  percentage: string;
}

export interface ServiceConsumption {
  water: Consumption,
  energy: Consumption
}