/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#fff1f1',
          100: '#ffe0e0',
          200: '#ffc5c5',
          300: '#ff9d9d',
          400: '#ff6464',
          500: '#fb3232',
          600: '#e81010',
          700: '#c00a0a',
          800: '#7f0d0d',
          900: '#6d0c0c',
        },
      },
    },
  },
  plugins: [],
}