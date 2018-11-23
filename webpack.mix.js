let mix = require("laravel-mix");
let tailwind = require("tailwindcss");
require("laravel-mix-purgecss");

mix
  .js("resources/js/app.js", "public/js")
  .postCss("resources/css/animate.css", "public/css")
  .postCss("resources/css/cronmon.css", "public/css", [
    tailwind("tailwind.js")
  ])
  .purgeCss();
