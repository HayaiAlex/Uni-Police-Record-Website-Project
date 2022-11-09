/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./src/**/*.{html,js,svelte,ts}'],
  theme: {
    extend: {
      backgroundImage: {
        'traffic': "url('src/assets/Simon-Klasen-Traffic.jpg')"
      }
    },
    plugins: []
  }
};