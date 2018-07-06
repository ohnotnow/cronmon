let mix = require("laravel-mix");
let tailwind = require("tailwindcss");
require("laravel-mix-purgecss");

mix
  .js("resources/assets/js/app.js", "public/js")
  .postCss("resources/assets/css/animate.css", "public/css")
  .postCss("resources/assets/css/cronmon.css", "public/css", [
    tailwind("tailwind.js")
  ])
  .purgeCss();
