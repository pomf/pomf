module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    swig: {
      dist: {
        init: {
          allowErrors: false
        },
        banners: grunt.file.readJSON('pages/banners.json'),
        max_upload_size: 120,
        dest: 'dist',
        generateSitemap: false,
        generateRobotstxt: false,
        src: [
          'pages/index.swig',
          'pages/faq.swig',
          'pages/tools.swig',
          'pages/other.swig',
          'pages/nojs.swig',
          'pages/ca.swig'
        ],
        siteUrl: 'https://pantsu.cat/',
        production: false,
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
            '// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat\n',
        footer: '\n// @license-end',
        screwIE8: true
      },
      dist: {
        files: {
          'dist/pomf.min.js': [
            'js/zepto.js',
            'js/cheesesteak.js',
            'js/cabinet.js',
            'js/pomf.js'
          ]
        }
      }
    },
    cssmin: {
      options: {
        banner: '/*! <%= pkg.name %> (<%= pkg.repository.url %>) @ <%= grunt.template.today("yyyy-mm-dd") %> */\n\n'
      },
      dist: {
        files: {
          'dist/pomf.min.css': [
            'css/pomf.css'
          ]
        }
      }
    },
    mkdir: {
      options: {
        mode: 0700,
        create: ['dist/img']
      }
    },
    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: 'img/',
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
        }]
      }
    },
    jshint: {
      options: {
        curly: true,
        undef: true
      },
      all: [
        'js/pomf.js',
        'js/cabinet.js',
        'js/cheesesteak.js'
      ]
    }
  });

  grunt.loadNpmTasks('grunt-swig');
  grunt.loadNpmTasks('grunt-mkdir');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-htmlmin');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.registerTask('default', ['mkdir', 'swig', 'htmlmin', 'cssmin', 'uglify', 'imagemin', 'copy']);
  grunt.registerTask('test', ['jshint']);
};
