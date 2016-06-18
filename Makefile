all: mkdirs swig min-css min-js copy

swig:
	@node node_modules/swig/bin/swig.js render -j dist.json templates/faq.swig > dist/faq.html 
	@node node_modules/swig/bin/swig.js render -j dist.json templates/index.swig > dist/index.html 
	@node node_modules/swig/bin/swig.js render -j dist.json templates/tools.swig > dist/tools.html 

mkdirs:
	@mkdir -p ./dist/img

min-css:
	@node ./node_modules/.bin/cleancss --s0 ./static/css/pomf.css > ./dist/pomf.min.css

min-js:
	@echo "// @source https://git.pantsu.cat/pantsu/pomf/tree/js" >> ./dist/pomf.min.js 
	@echo "// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat" >> ./dist/pomf.min.js
	@node ./node_modules/.bin/uglifyjs  --screw-ie8 ./static/js/app.js >> ./dist/pomf.min.js 
	@echo "// @license-end" >> ./dist/pomf.min.js

copy:
	@cp -r ./static/php/* ./dist/
	@cp  ./static/img/*.png ./dist/img
	@cp  ./static/img/favicon.ico ./dist/favicon.ico


