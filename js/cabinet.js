/**
 * Copyright (c) 2013 Peter Lejeck <peter.lejeck@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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
