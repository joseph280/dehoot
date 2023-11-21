import React from 'react';

interface EnergyIconProps {
  className?: string;
}

export function CityEnergyIcon({ className }: EnergyIconProps) {
  return (
    <svg
      className={className}
      width="18"
      height="18"
      viewBox="0 0 18 18"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M11.0175 1.65748L3.24751 8.61748C2.76751 9.05248 3.03751 9.85498 3.68251 9.91498L9.75001 10.5L6.11251 15.57C5.94751 15.8025 5.97001 16.125 6.17251 16.3275C6.39751 16.5525 6.75001 16.56 6.98251 16.3425L14.7525 9.38248C15.2325 8.94748 14.9625 8.14498 14.3175 8.08498L8.25001 7.49998L11.8875 2.42998C12.0525 2.19748 12.03 1.87498 11.8275 1.67248C11.7215 1.56419 11.5771 1.50193 11.4256 1.49912C11.274 1.49632 11.1275 1.55319 11.0175 1.65748Z"
        fill="#EAB308"
      />
      <rect x="0.5" y="0.5" width="17" height="17" rx="8.5" stroke="#EAB308" />
    </svg>
  );
}
