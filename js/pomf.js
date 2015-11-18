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
  var $uploadInput = $('#upload-input');
  var $uploadBtn = $('#upload-btn');
  var $uploadFiles = $('#upload-filelist');

  var $btnContent = '';

  var MAX_SIZE = (function(node) {
    var max = node.attr('data-max-size') || '120MiB';
    var num = parseInt(/([0-9,]+).*/.exec(max)[1].replace(',', ''), 10);
    var unit = /(?:([KMGTPEZY])(i)?B|([BKMGTPEZY]))/.exec(max) || ['B', '', ''];

    var oneUnit = Math.pow(
      (unit[2] === 'i' ? 1024 : 1000),
      'BKMGTPEZY'.indexOf(unit[1])
    );

    return num * oneUnit;
  })($uploadInput);

  var createRow = function(filename, size, extra) {
    var $rowItem = $('<li class=file>');
    var $rowName = $('<span class=file-name>');
    var $rowProg = $('<div class="file-progress progress-outer">');
    var $rowSize = $('<span class=file-size>');
    var $rowUrl = $('<span class=file-url>');

    $rowItem.addClass(extra || '');

    $('<div class=progress-inner>').appendTo($rowProg);

    $rowItem.attr('data-filename', escape(filename));
    $rowName.text(filename);
    $rowSize.text(size);

    $rowItem.append($rowName, $rowProg, $rowSize, $rowUrl);

    return $rowItem;
  };

  if (!$.hasFileAPI()) {
    $('#no-file-api').show();
    $uploadBtn.hide();
  }

  $uploadBtn.cabinet($uploadInput);

  $uploadBtn.on('dragenter', function(evt) {
    if (this === evt.target) {
      $(this).addClass('drop');
      $btnContent = $(this).html();
      $(this).html('Drop it here~');
    }
  });

  $uploadBtn.on('drop', function() {
    $(this).trigger('dragleave');
  });

  $uploadBtn.on('dragleave', function(evt) {
    var node = evt.target;
    do {
      if (node === this) {
        $(this).removeClass('drop');
        $(this).html($btnContent);
        break;
      }
    } while (node === node.parentNode);
  });

  $uploadBtn.on('change', function() {
    var files = $uploadInput[0].filelist;
    var totalRow = createRow('', files.humanSize, 'total');
    var $totalName = $('.file-name', totalRow);
    var UPLOAD_ERR_MAX_SIZE = 'onii-chan y-your upload is t-too big&hellip;';
    var UPLOAD_ERR_FAILED = 'Something went wrong; try again later.';
    var up = files.upload('upload.php');

    var eachRow = function(files, fn) {
      var hits = {};
      files.forEach(function(file) {
        var row = $($('li[data-filename="' +
          escape(file.name) + '"]')[hits[file.name] || 0]);
        ++hits[file.name];
        fn.call(row, row, file, files);
      });
    };

    $uploadFiles.empty().removeClass('error completed');

    files.forEach(function(file) {
      createRow(file.name, file.humanSize).appendTo($uploadFiles);
    });

    totalRow.appendTo($uploadFiles);

    if (files.size > MAX_SIZE) {
      $uploadFiles.addClass('error');
      $totalName.html(UPLOAD_ERR_MAX_SIZE);
      return;
    }

    up.on('uploadprogress', function(evt, files) {
      eachRow(files, function(row, file) {
        $('.progress-inner', row).width((file.percentUploaded * 100) + '%');
      });

      $('.progress-inner', totalRow).width((files.percentUploaded * 100) + '%');
    });

    up.on('uploadcomplete', function() {
      $('.progress-inner').width('100%');
      $totalName.html('Grabbing URLs&hellip;');
    });

    up.on('load', function(evt, response) {
      var res = JSON.parse(response);

      switch (evt.target.status) {
      case 200:
        if (!res.success) {
          $uploadFiles.addClass('error');
          $totalName.text(UPLOAD_ERR_FAILED);
          break;
        }

        eachRow(res.files, function(row, file) {
          var $link = $('<a>');

          $link.attr('href', file.url)
               .attr('target', '_BLANK')
               .text(file.url.replace('http://', '').replace('https://', ''));

          $('.file-url', row).append($link);
        });

        $uploadFiles.addClass('completed');
        $totalName.text('Done!');
        break;
      case 413:
        $uploadFiles.addClass('error completed');
        $totalName.html(UPLOAD_ERR_MAX_SIZE);
        break;
      default:
        $uploadFiles.addClass('error completed');
        $totalName.text(UPLOAD_ERR_FAILED);
      }
    });

    up.upload();
  });
});
