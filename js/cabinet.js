/* jshint browser:true, jquery:true */
(function($) {
  $.hasFileAPI = function hasFileAPI() {
    return (window.FormData !== undefined);
  };

  $.fn.cabinet = function(input) {
    input = $(input);

    var _this = this;
    var passthrough = function(outer, inner, prevent, fn) {
      _this.on(outer, function(e) {
        if (inner === 'click') {
          input[0].click();
        } else {
          input.trigger(inner);
        }
        if (prevent) {
          e.preventDefault();
        }
        if (fn) {
          fn(e);
        }
      }, false);
    };

    input[0].filelist = Object.create(FileList);

    input.on('change', function(e) {
      this.filelist = e.target.files;
      _this.change();
    });

    passthrough('dragenter', 'dragenter', true);
    passthrough('dragover', 'dragover', true);
    passthrough('dragleave', 'dragleave', true);
    passthrough('click', 'click', false);
    passthrough('drop', 'dragleave', true, function(e) {
      input[0].filelist = e.dataTransfer.files;
      _this.change();
    });
  };
})($);
