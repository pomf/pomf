module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    swig: {
      dist: {
        init: {
          allowErrors: false
        },
        banners: grunt.file.readJSON('templates/banners.json'),
        dest: 'dist',
        generateRobotstxt: false,
        generateSitemap: false,
        max_upload_size: 128,
        pkgVersion: '<%= pkg.version %>',
        production: false,
        siteUrl: 'https://pantsu.cat/',
        src: [
          'templates/index.swig',
          'templates/faq.swig',
          'templates/tools.swig'
        ],
      }
    },
    htmlmin: {
      dist: {
        options: {
          removeComments: true,
          collapseWhitespace: true,
          removeEmptyAttributes: true
        },
        files: [{
          expand: true,
          cwd: 'dist/',
          src: '*.html',
          dest: 'dist/'
        }]
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> (<%= pkg.repository.url %>) @ <%= grunt.template.today("yyyy-mm-dd") %> */\n' + 
          '// @source https://git.pantsu.cat/pantsu/pomf/tree/js\n' + 
          '// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat\n',
        footer: '\n// @license-end',
        screwIE8: true
      },
      dist: {
        files: {
          'dist/pomf.min.js': [
            'static/js/app.js'
          ]
        }
      }
    },
    cssmin: {
      dist: {
        files: {
          'dist/pomf.min.css': [
            'static/css/pomf.css'
          ]
        }
      }
    },
    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: 'static/img/',
          src: '**/*.{png,jpg,gif}',
          dest: 'dist/img/'
        }]
      }
    },
    copy: {
      dist: {
        files: [{
          expand: true,
          cwd: 'static/',
          src: '**',
          dest: 'dist/'
        },
        {
          expand: true,
          cwd: 'img/',
          src: '**',
          dest: 'dist/img/'
        }]
      }
    }
  });

  grunt.loadNpmTasks('grunt-swig');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-htmlmin');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.registerTask('default', ['swig', 'htmlmin', 'cssmin', 'uglify', 'imagemin', 'copy']);
};
