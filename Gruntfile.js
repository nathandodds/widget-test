module.exports = function(grunt) {

  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),

    /**
     * Uglify task to minify all javscript files
     * seperating out all script files into a development directory
     * and then having all 'production' scripts within a product directory
     *
     * Note: Will need a bit of work to target the plugins folder
     *       independently to the main apps - as we may have unminified
     *       versions - aswell as our Backbone views.
     */
    uglify: {
      options: {
        mangle: false
      },
      files: { 
          src: 'assets/scripts/development/*.js',  // source files mask
          dest: 'assets/scripts/production/',    // destination folder
          expand: true,    // allow dynamic building
          flatten: true,   // remove all unnecessary nesting
          ext: '.min.js'   // replace .js to .min.js
      }
    },

    /**
     * Sass module compiles all SASS files
     * This quickly avoids having to open and save
     * every single css file if a conflict is present
     */
    sass: {
      dist: {
        files: [{
          expand: true,
          cwd: 'assets/styles/sass/',
          src: ['*.scss'],
          dest: 'assets/styles/',
          ext: '.css'
        }]
      }
    },

    /**
     * Image minification for compressing images
     * accross the project and moving them into their own folder
     * all references once production has gone through points to those
     */
    imagemin: {
        png: {
            options: {
                optimizationLevel: 7
            },
            files: [
                {
                    expand: true,
                    cwd: 'assets/images/',
                    src: ['**/*.png'],
                    dest: 'assets/images/compressed/',
                    ext: '.png'
                }
            ]
        },
        jpg: {
            options: {
                progressive: true
            },
            files: [
                {
                    expand: true,
                    cwd: 'assets/images/',
                    src: ['**/*.jpg'],
                    dest: 'assets/images/compressed/',
                    ext: '.jpg'
                }
            ]
        }
    },

    /**
     * PHP Code Standards fixer
     * Runs through the app (main code base for each project)
     * to ensure conforms to a standard
     */
    phpcsfixer: {
        app: {
            dir: 'app'
        },
        options: {
            bin: 'php-cs-fixer',
            ignoreExitCode: true,
            level: 'all',
            quiet: false,
            diff: true,
            verbose: true
        }
    },

    /** 
     * Minifies the CSS after SASS has been compiled
     */
    cssmin: {
      minify: {
        expand: true,
        cwd: 'assets/styles/',
        src: ['*.css', '!*.min.css'],
        dest: 'assets/styles/',
        ext: '.css'
      }
    }

  });
  
  // Load the plugin that provides the defined grunt tasks
  grunt.loadNpmTasks('grunt-php-cs-fixer');

  grunt.loadNpmTasks('grunt-contrib-imagemin');

  grunt.loadNpmTasks('grunt-contrib-sass');

  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.loadNpmTasks('grunt-contrib-cssmin');

  // Default task(s).
  grunt.registerTask('default', ['uglify', 'sass', 'imagemin']);

  // Compile Sass files and then compress and minify them
  grunt.registerTask('sassmin', ['sass', 'cssmin']);

};

