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
    extend: {},
  },
  plugins: [],
}

