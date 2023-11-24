/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/*.html.twig",
    "./templates/**/*.html.twig",
    "./templates/**/**/*.html.twig",
    "./templates/**/**/**/*.html.twig",
    "./src/Form/**.php",
    "./node_modules/flowbite/**/*.js", // flowbite package
    "./vendor/symfony/twig-bridge/Resources/views/Form/tailwind_2_layout.html.twig", // tailwind form theme
  ],
  theme: {
    extend: {
      colors: {
        background: "#f3f3f8",
        // main: {
        //   50: "#f3f6fc",
        //   100: "#e7edf7",
        //   200: "#c9d8ee",
        //   300: "#9ab9df",
        //   400: "#6393cd",
        //   500: "#3f76b8",
        //   600: "#2e5d9b",
        //   700: "#274b7d",
        //   800: "#234069",
        //   900: "#223758",
        //   950: "#0c131f",
        // },
        main: {
          50: "#f2fbf4",
          100: "#e2f6e6",
          200: "#c6ecce",
          300: "#9adbaa",
          400: "#66c27c",
          500: "#41a659",
          600: "#35944c",
          700: "#296c3a",
          800: "#255632",
          900: "#20472a",
          950: "#0d2614",
        },
      },
    },
  },
  plugins: [],
};
