/**
 * Copyright (c) 2016 Luminarys <postmaster@gensok.io>
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

document.addEventListener('DOMContentLoaded', function () {

  function addRow(file) {
    var row = document.createElement('li');

    var name = document.createElement('span');
    name.textContent = file.name;
    name.className = 'file-name';

    var progressIndicator = document.createElement('span');
    progressIndicator.className = 'progress-percent';
    progressIndicator.textContent = '0%';

    var progressBar = document.createElement('progress');
    progressBar.className = 'file-progress';
    progressBar.setAttribute('max', '100');
    progressBar.setAttribute('value', '0');

    row.appendChild(name);
    row.appendChild(progressBar);
    row.appendChild(progressIndicator);

    document.getElementById('upload-filelist').appendChild(row);
    return row;
  }

  function handleUploadProgress(evt) {
    var xhr = evt.target;
    var bar = xhr.bar;
    var percentIndicator = xhr.percent;

    if (evt.lengthComputable) {
      var progressPercent = Math.floor((evt.loaded / evt.total) * 100);
      bar.setAttribute('value', progressPercent);
      percentIndicator.textContent = progressPercent + '%';
    }
  }

  function handleUploadComplete(evt) {
    var xhr = evt.target;
    var bar = xhr.bar;
    var row = xhr.row;
    var percentIndicator = xhr.percent;

    percentIndicator.style.visibility = 'hidden';
    bar.style.visibility = 'hidden';
    row.removeChild(bar);
    row.removeChild(percentIndicator);
    var respStatus = xhr.status;

    var url = document.createElement('span');
    url.className = 'file-url';
    row.appendChild(url);

    var link = document.createElement('a');
    if (respStatus === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        link.textContent = response.files[0].url.replace(/.*?:\/\//g, '');
        link.href = response.files[0].url;
        url.appendChild(link);
      } else {
        bar.innerHTML = 'Error: ' + response.description;
      }
    } else if (respStatus === 413) {
      link.textContent = 'File Too big!';
      url.appendChild(link);
    } else {
      link.textContent = 'Server error!';
      url.appendChild(link);
    }
  }

  function uploadFile(file, row) {
    var bar = row.querySelector('.file-progress');
    var percentIndicator = row.querySelector('.progress-percent');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/upload.php');

    xhr['row'] = row;
    xhr['bar'] = bar;
    xhr['percent'] = percentIndicator;
    xhr.upload['bar'] = bar;
    xhr.upload['percent'] = percentIndicator;

    xhr.addEventListener('load', handleUploadComplete, false);
    xhr.upload.onprogress = handleUploadProgress;

    var form = new FormData();
    form.append('files[]', file);
    xhr.send(form);
  }

  function stopDefaultEvent(evt) {
    evt.stopPropagation();
    evt.preventDefault();
  }

  function handleDrag(state, element, evt) {
    stopDefaultEvent(evt);
    if (state.dragCount == 1) {
      element.textContent = 'Drop it here~';
    }

    state.dragCount += 1;
  }

  function handleDragAway(state, element, evt) {
    stopDefaultEvent(evt);
    state.dragCount -= 1;
    if (state.dragCount == 0) {
      element.textContent = 'Select or drop file(s)';
    }
  }

  function handleDragDrop(state, element, evt) {
    stopDefaultEvent(evt);
    handleDragAway(state, element, evt);
    var len = evt.dataTransfer.files.length;
    for (var i = 0; i < len; i++) {
      var file = evt.dataTransfer.files[i];
      var row = addRow(file);
      uploadFile(file, row);
    }
  }

  function uploadFiles(evt) {
    var len = evt.target.files.length;
    for (var i = 0; i < len; i++) {
      var file = evt.target.files[i];
      var row = addRow(file);
      uploadFile(file, row);
    }
  }

  function selectFiles(target, evt) {
    stopDefaultEvent(evt);
    target.click();
  }

  var state = { dragCount: 0 };
  var uploadButton = document.getElementById('upload-btn');
  window.addEventListener('dragenter', handleDrag.bind(this, state, uploadButton), false);
  window.addEventListener('dragleave', handleDragAway.bind(this, state, uploadButton), false);
  window.addEventListener('drop', handleDragAway.bind(this, state, uploadButton), false);
  window.addEventListener('dragover', stopDefaultEvent, false);

  var uploadInput = document.getElementById('upload-input');
  uploadInput.addEventListener('change', uploadFiles);
  uploadButton.addEventListener('click', selectFiles.bind(this, uploadInput));
  uploadButton.addEventListener('drop', handleDragDrop.bind(this, state, uploadButton), false);
  document.getElementById('upload-form').classList.add('js');
});
