DESTDIR="./dist"
TMPDIR := $(shell mktemp -d)
TAR="/bin/tar"

all: swig htmlmin min-css min-js

swig:
	@node node_modules/swig/bin/swig.js render -j dist.json templates/faq.swig > $(DESTDIR)/faq.html 
	@node node_modules/swig/bin/swig.js render -j dist.json templates/index.swig > $(DESTDIR)/index.html 
	@node node_modules/swig/bin/swig.js render -j dist.json templates/tools.swig > $(DESTDIR)/tools.html 

htmlmin:
	@node node_modules/htmlmin/bin/htmlmin dist/index.html -o $(DESTDIR)/index.html 
	@node node_modules/htmlmin/bin/htmlmin dist/faq.html -o $(DESTDIR)/faq.html 
	@node node_modules/htmlmin/bin/htmlmin dist/tools.html -o $(DESTDIR)/tools.html 
	

installdirs:
	@mkdir -p $(DESTDIR)/
	@mkdir -p $(DESTDIR)/img	

min-css:
	@node ./node_modules/.bin/cleancss --s0 ./static/css/pomf.css > $(DESTDIR)/pomf.min.css

min-js:
	@echo "// @source https://github.com/pomf/pomf/tree/master/static/js" > $(DESTDIR)/pomf.min.js 
	@echo "// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat" >> $(DESTDIR)/pomf.min.js
	@node ./node_modules/.bin/uglifyjs  --screw-ie8 ./static/js/app.js >> $(DESTDIR)/pomf.min.js 
	@echo "// @license-end" >> ./dist/pomf.min.js

install: installdirs
	@cp -vr ./php/* $(DESTDIR)/
	@cp -v ./static/img/*.png $(DESTDIR)/img/
	@cp -vT ./static/img/favicon.ico $(DESTDIR)/favicon.ico

dist:
	DESTDIR=$(TMPDIR)
	export DESTDIR
	install
	@$(TAR) cJf pomf.tar.xz DESTDIR/
	@rm -rf $(TMPDIR)
	

clean:
	
uninstall:
	@rm -rf $(DESTDIR)/
