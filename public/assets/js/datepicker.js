$(function() {
  'use strict';

  if($('#datePickerExample').length) {
    
    $('#datePickerExample').datepicker({
      format: "mm/dd/yyyy",
      todayHighlight: true,
      autoclose: true
    });
    
  }

  if($('#datePickerExamples').length) {
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#datePickerExamples').datepicker({
      format: "mm/dd/yyyy",
      todayHighlight: true,
      autoclose: true
    });
    $('#datePickerExamples').datepicker('setDate', today);
  }
});