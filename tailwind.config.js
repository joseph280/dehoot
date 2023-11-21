module.exports = {
  content: ['./resources/**/*.tsx'],
  theme: {
    extend: {
      fontFamily: {
        IBMPlexMono: ['IBM Plex Mono', 'monospace'],
        Poppins: ['Poppins', 'sans-serif'],
      },
      boxShadow: {
        'center-2xl': ' 0px 0px 10px 0px rgba(0, 0, 0, 1.0)',
      },
      screens: {
        standalone: { raw: '(display-mode: standalone)' },
      },
      colors: {
        dehoot: {
          sky: {
            50: '#E7FCFD',
            100: '#CFF9FC',
            200: '#A0F2F8',
            300: '#6BECF5',
            400: '#3BE5F2',
            500: '#10DCE9',
            600: '#0DAFBA',
            700: '#0A828A',
            800: '#07595F',
            900: '#032D30',
          },
          blue: {
            50: '#EEF4FB',
            100: '#D9E7F7',
            200: '#B3CFEF',
            300: '#8DB7E7',
            400: '#679FDF',
            500: '#4288D7',
            600: '#276BB9',
            700: '#1D508B',
            800: '#14365D',
            900: '#0A1B2E',
          },
          purple: {
            50: '#F7E9FB',
            100: '#F0D3F8',
            200: '#E0A8F0',
            300: '#D17CE9',
            400: '#C251E1',
            500: '#B225D9',
            600: '#8F1EAE',
            700: '#6B1683',
            800: '#470F57',
            900: '#24072C',
          },
        },
      },
    },
  },
  plugins: [],
};
