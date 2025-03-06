$(function() {
  'use strict';

  $("exampleDropzone").dropzone({
    url: 'nobleui.com',
    maxFiles: 5,
    acceptedFiles: 'image/*',
    addRemoveLinks: true,
    maxFilesize: 1000000,
    
  });
});