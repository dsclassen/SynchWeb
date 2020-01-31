// postcss.config.js
const purgecss = require('@fullhuman/postcss-purgecss')({

  // Specify the paths to all of the template files in your project
  content: [
    './src/index.php',
    './src/**/*.html',
    './src/**/*.vue',
    './src/**/*.js', /* Lots of classes are used within inline templates... */
  ],
})

module.exports = ({env}) => (
  {
    plugins: [
      require('postcss-import'),
      require('tailwindcss'),
      require('autoprefixer'),
      ...process.env.NODE_ENV === 'production' ? [purgecss]: [],
    ]
  }
)
