module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    swig: {
      dist: {
        init: {
          allowErrors: false
        },
        banners: grunt.file.readJSON('pages/banners.json'),
        max_upload_size: 128,
        dest: 'dist',
        generateSitemap: false,
        generateRobotstxt: false,
        src: [
          'pages/index.swig',
          'pages/faq.swig',
          'pages/tools.swig',
          'pages/other.swig',
          'pages/nojs.swig'
        ],
        siteUrl: 'https://pantsu.cat/',
        production: false,
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> (<%= pkg.repository.url %>) @ <%= grunt.template.today("yyyy-mm-dd") %> */\n' + 
          '// @source https://git.pantsu.cat/pantsu/pomf/src/master/js\n' + 
          '// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat\n',
        footer: '\n// @license-end',
        screwIE8: true
      },
      dist: {
        files: {
          'dist/pomf.min.js': [
            'js/app.js'
          ]
        }
      }
    },
    cssmin: {
      dist: {
        files: {
          'dist/pomf.min.css': [
            'css/pomf.css'
          ]
        }
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
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.registerTask('default', ['swig', 'cssmin', 'uglify', 'copy']);
};
