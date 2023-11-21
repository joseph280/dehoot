import { ZoomLimits } from '@/game/scenes/main';

export function classNames(...classes: string[]): string {
  return classes.filter(Boolean).join(' ');
}

export function clamp(number: number, min: number, max: number): number {
  return Math.min(Math.max(number, min), max);
}

export function generateMinAndMaxZoom(): ZoomLimits {
  let minZoom = 0.3;
  let maxZoom = 0.6;

  if (window.innerWidth > window.innerHeight) {
    if (window.innerWidth >= 1920) {
      minZoom = 0.4;
      maxZoom = 0.8;
    }

    if (window.innerWidth >= 2560) {
      minZoom = 0.5;
      maxZoom = 0.9;
    }

    if (window.innerWidth >= 3200) {
      minZoom = 0.65;
      maxZoom = 1;
    }
  }

  if (window.innerHeight > window.innerWidth) {
    if (window.innerHeight >= 1080) {
      minZoom = 0.3;
      maxZoom = 0.6;
    }
  }

  return { minZoom, maxZoom };
}
