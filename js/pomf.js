/**
 * Copyright (c) 2013 Peter Lejeck <peter.lejeck@gmail.com>
 * Copyright (c) 2015 the Pantsu.cat developers <hostmaster@pantsu.cat>
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

/* jshint browser:true, jquery:true */
$(function() {
  var uploadInput = $('#upload-input');
  var uploadBtn   = $('#upload-btn');
  var uploadFiles = $('#upload-filelist');

  var btnContent = '';

  if (!$.hasFileAPI()) {
    $('#no-file-api').show();
    uploadBtn.hide();
  }

  uploadBtn.cabinet(uploadInput);

  uploadBtn.on('dragenter', function(e) {
    if (this === e.target) {
      $(this).addClass('drop');
      btnContent = $(this).html();
      $(this).html('Drop it here~');
    }
  });

  uploadBtn.on('drop', function(e) {
    $(this).trigger('dragleave');
  });

  uploadBtn.on('dragleave', function(e) {
    var node = e.target;
    do {
      if (node === this) {
        $(this).removeClass('drop');
        $(this).html(btnContent);
        break;
      }
    } while (node === node.parentNode);
  });

  var MAX_SIZE = (function(node) {
    var max = node.attr('data-max-size') || '120MiB';
    var num = parseInt(/([0-9,]+).*/.exec(max)[1].replace(',', ''), 10);
    var unit = /(?:([KMGTPEZY])(i)?B|([BKMGTPEZY]))/.exec(max) || ['B', '', ''];

    var oneUnit = Math.pow(
      (unit[2] === 'i' ? 1024 : 1000),
      'BKMGTPEZY'.indexOf(unit[1])
    );

    return num * oneUnit;
  })(uploadInput);

  var createRow = function(filename, size, extra) {
    var rowItem = $('<li class=file>');
    var rowName = $('<span class=file-name>');
    var rowProg = $('<div class="file-progress progress-outer">');
    var rowSize = $('<span class=file-size>');
    var rowUrl  = $('<span class=file-url>');

    rowItem.addClass(extra || '');

    $('<div class=progress-inner>').appendTo(rowProg);

    rowItem.attr('data-filename', escape(filename));
    rowName.text(filename);
    rowSize.text(size);

    rowItem.append(rowName, rowProg, rowSize, rowUrl);

    return rowItem;
  };

  uploadBtn.on('change', function(e) {
    uploadFiles.empty().removeClass('error completed');

    var files = uploadInput[0].filelist;

    files.forEach(function(file) {
      createRow(file.name, file.humanSize).appendTo(uploadFiles);
    });

    var totalRow = createRow('', files.humanSize, 'total');
    totalRow.appendTo(uploadFiles);

    var totalName = $('.file-name', totalRow);

    var UPLOAD_ERR_MAX_SIZE = 'onii-chan y-your upload is t-too big&hellip;';
    var UPLOAD_ERR_FAILED = 'Something went wrong; try again later.';

    if (files.size > MAX_SIZE) {
      uploadFiles.addClass('error');
      totalName.html(UPLOAD_ERR_MAX_SIZE);
      return;
    }

    var up = files.upload('upload.php');

    var eachRow = function(files, fn) {
      var hits = {};
      files.forEach(function(file) {
        ++hits[file.name];
        var row = $($('li[data-filename="' +
          escape(file.name) + '"]')[hits[file.name] || 0]);
        fn.call(row, row, file, files);
      });
    };

    up.on('uploadprogress', function(e, files) {
      eachRow(files, function(row, file, files) {
        $('.progress-inner', row).width((file.percentUploaded * 100) + '%');
      });

      $('.progress-inner', totalRow).width((files.percentUploaded * 100) + '%');
    });

    up.on('uploadcomplete', function(e) {
      $('.progress-inner').width('100%');
      totalName.html('Grabbing URLs&hellip;');
    });

    up.on('load', function(e, response) {
      switch (e.target.status) {
      case 200:
        var res = JSON.parse(response);
        if (!res.success) {
          uploadFiles.addClass('error');
          totalName.text(UPLOAD_ERR_FAILED);
          break;
        }

        eachRow(res.files, function(row, file, files) {
          var link = $('<a>');

          link.attr('href', file.url)
              .attr('target', '_BLANK')
              .text(file.url.replace('http://', '').replace('https://', ''));

          $('.file-url', row).append(link);
        });

        uploadFiles.addClass('completed');
        totalName.text('Done!');
        break;
      case 413:
        uploadFiles.addClass('error completed');
        totalName.html(UPLOAD_ERR_MAX_SIZE);
        break;
      default:
        uploadFiles.addClass('error completed');
        totalName.text(UPLOAD_ERR_FAILED);
      }
    });

    up.upload();
  });
});
