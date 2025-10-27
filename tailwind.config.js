/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');

module.exports = {
  content: ["*.{php, js}", "admin/*.{php, js}", "files/js_files/*.{js}"],
  theme: {
    extend: {},
    colors: {
      'main': '#EBEEFF',
      'container': '#F0F2FF',
      'temp':'#3351d8',
      transparent: colors.transparent,
      current: colors.current,
      indigo: colors.indigo,
      red: colors.red,
      orange: colors.orange,
      amber: colors.amber,
      lime: colors.lime,
      emerald: colors.emerald,
      teal: colors.teal,
      cyan: colors.cyan,
      blue: colors.blue,
      black: colors.black,
      white: colors.white,
      gray: colors.slate,
      green: colors.emerald,
      purple: colors.violet,
      yellow: colors.amber,
      violet: colors.violet,
      fuchsia: colors.fuchsia,
      rose: colors.rose,
      pink: colors.fuchsia,
      zinc: colors.zinc,
      slate: colors.slate,
      neutral: colors.neutral,
      stone: colors.stone,
    }
  },
  plugins: [],
}

