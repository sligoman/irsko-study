const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: false,
  theme: {
    extend: {
      fontFamily: {
        sans: ['Raleway', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: {
          '50': '#F0FAF9',
          '100': '#D9F0EC',
          '200': '#BFE6DF',
          '300': '#A5DCD2',
          '400': '#8BD2C5',
          '500': '#72C8B9',
          '600': '#5DB5A7',
          '700': '#479494',
          '800': '#318081',
          '900': '#0E4A32',
          
        },
        accent: {
          '50': '#FFF3E0',
          '100': '#FFE0B2',
          '200': '#FFCC80',
          '300': '#FFB74D',
          '400': '#FFA726',
          '500': '#FF9800',
          '600': '#FB8C00',
          '700': '#F57C00',
          '800': '#EF6C00',
          '900': '#E65100',          
        },
      },
      animation: {
        'zoom-slow': 'zoom-slow 20s ease-in-out infinite',
        'zoom-in': 'zoom-in 5s ease-in-out forwards',
      },
      keyframes: {
        'zoom-slow': {
          '0%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.1)' },
          '100%': { transform: 'scale(1.2)' },
        },
        'zoom-in': {
          '0%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.2)' },
          '100%': { transform: 'scale(1)' },
        },
      }
    },
  },
  variants: {
    scale: ['responsive', 'hover', 'focus', 'group-hover'],
    textColor: ['responsive', 'hover', 'focus', 'group-hover'],
    opacity: ['responsive', 'hover', 'focus', 'group-hover'],
    backgroundColor: ['responsive', 'hover', 'focus', 'group-hover'],
  },
  safelist: [
    {
      pattern: /(text-|bg-|content-|place-|justify-|items-|leading-|px-|py-|self-|w-|h-|inset-|top-|bottom-|right-|left-|object-).*/,
    },
  ],
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
