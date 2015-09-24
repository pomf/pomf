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

/* jshint browser:true, jquery:true */
$(function() {
  var uploadInput = $('#upload-input'),
      uploadBtn   = $('#upload-btn'),
      uploadFiles = $('#upload-filelist');

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
    node = e.target;
    do {
      if (node === this) {
        $(this).removeClass('drop');
        $(this).html(btnContent);
        break;
      }
    } while (node = node.parentNode);
  });

  var MAX_SIZE = (function(node) {
    var max = node.attr('data-max-size') || '120MiB';
    var num = parseInt(/([0-9,]+).*/.exec(max)[1].replace(',', ''));
    var unit = /(?:([KMGTPEZY])(i)?B|([BKMGTPEZY]))/.exec(max) || ['B', '', ''];

    var oneUnit = Math.pow(
      (unit[2] === 'i' ? 1024 : 1000),
      'BKMGTPEZY'.indexOf(unit[1])
    );

    return num * oneUnit;
  })(uploadInput);

  var createRow = function(filename, size, extra) {
    var rowItem = $('<li class=file>'),
        rowName = $('<span class=file-name>'),
        rowProg = $('<div class="file-progress progress-outer">'),
        rowSize = $('<span class=file-size>'),
        rowUrl  = $('<span class=file-url>');

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

    if (files.size > MAX_SIZE) {
      uploadFiles.addClass('error');

      $('.file-name', totalRow).html('onii-chan y-your upload is t-too ' +
        'big&hellip;');
      return;
    }

    var up = files.upload('upload.php');

    var eachRow = function(files, fn) {
      var hits = {};
      files.forEach(function(file) {
        ++hits[file.name] || (hits[file.name] = 0);
        var row = $($('li[data-filename="' + 
          escape(file.name) + '"]')[hits[file.name] || 0]);
        fn.call(row, row, file, files);
      });
    };

    var totalName = $('.file-name', totalRow);

    up.on('uploadprogress', function(e, files) {
      eachRow(files, function(row, file, files) {
        $('.progress-inner', row).width((file.percentUploaded * 100) + '%');
      });
      $('.progress-inner', totalRow).width((files.percentUploaded * 100) + '%');
    });

    up.on('uploadcomplete', function(e) {
      $('.progress-inner').width('100%');
      totalName.text('Grabbing URLs...');
    });

    up.on('load', function(e, res) {
      switch (e.target.status) {
        case 200:
          var res = JSON.parse(res);
          if (!res.success) {
            uploadFiles.addClass('error');
            $('.file-name', totalRow).text('Something went wrong; try again ' +
              'later.');
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
          // Terrible work-around, but necessary since otherwise the '&hellip;'
          // entity is left decoded.
          totalName.html($('<div/>').html('onii-chan, y-your upload is t-too ' +
            'big&hellip;').text());
          break;
        default:
          uploadFiles.addClass('error completed');
          totalName.text('Something went wrong; try again later.');
      }
    });
    up.upload();
  });
});
