all: installdirs swig htmlmin min-css min-js install

swig:
	@node node_modules/swig/bin/swig.js render -j dist.json templates/faq.swig > dist/faq.html 
	@node node_modules/swig/bin/swig.js render -j dist.json templates/index.swig > dist/index.html 
	@node node_modules/swig/bin/swig.js render -j dist.json templates/tools.swig > dist/tools.html 

htmlmin:
	@node node_modules/htmlmin/bin/htmlmin dist/index.html -o dist/index.html 
	@node node_modules/htmlmin/bin/htmlmin dist/faq.html -o dist/faq.html 
	@node node_modules/htmlmin/bin/htmlmin dist/tools.html -o dist/tools.html 
	

installdirs:
	@mkdir -p ./dist/img

min-css:
	@node ./node_modules/.bin/cleancss --s0 ./static/css/pomf.css > ./dist/pomf.min.css

min-js:
	@echo "// @source https://github.com/pomf/pomf/tree/master/static/js" > ./dist/pomf.min.js 
	@echo "// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat" >> ./dist/pomf.min.js
	@node ./node_modules/.bin/uglifyjs  --screw-ie8 ./static/js/app.js >> ./dist/pomf.min.js 
	@echo "// @license-end" >> ./dist/pomf.min.js

install:
	@cp -r ./php/* ./dist/
	@cp  ./static/img/*.png ./dist/img
	@cp  ./static/img/favicon.ico ./dist/favicon.ico

clean:
	@rm -rf ./dist/
