function setSelectedValue(selectObj, valueToSet) {
	for (var i = 0; i < selectObj.options.length; i++) {
		if(selectObj.options[i].value == valueToSet) {
			selectObj.options[i].selected = true;
			return;
		}
	}
}

function readOnly(isTrue){
	$("input" ).prop( "disabled", isTrue );
	$("select" ).prop( "disabled", isTrue );
	$("textarea").prop('disabled', isTrue);

	if (isTrue == true) {
		$(".add-on a").removeAttr("data-toggle");
	}else{
		$(".add-on a").attr("data-toggle", "modal");
	}
}




function cleanElements(){
	var elements = document.querySelectorAll("input[type=text]")
	for (var i = 0, element; element = elements[i++];) {
		element.value = "";
	}

	var elements_select = document.querySelectorAll("select")
	for (var i = 0, ele_select; ele_select = elements_select[i++];) {
		ele_select.selectedIndex = 0;
	}

	var elements_textArea = document.querySelectorAll("textarea")
	for (var i = 0, ele_textArea; ele_textArea = elements_textArea[i++];) {
		ele_textArea.value = "";
	}

	var elements_password = document.querySelectorAll("input[type=password]")
	for (var i = 0, ele_password; ele_password = elements_password[i++];) {
		ele_password.value="";
	}
}

$(document).ready(function() {
	$("#telephone").keydown(function (e) {
		var length = $(this).val().length;
		var groupSubmit = document.getElementById("groupSubmit");
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
             (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
             (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
               if( length === 1 ){
                groupSubmit.style.display="inline";
              }else if (length === 9){
                groupSubmit.style.display="none";
              }
              return;
            }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {  
            // Allow: Space, Comma
            if (e.keyCode !== 32 && e.keyCode !== 188){
            	e.preventDefault();
            }

          }

        // Validate length of telephone text
        if(length === 8 ){
        	groupSubmit.style.display="inline";
        }else{
        	groupSubmit.style.display="none";
        }
      });
});

function currentMonth(){

  var d = new Date();
  var month = d.getMonth()+1;
  var day = d.getDate();

  var output = d.getFullYear() + '-' + (month<10 ? '0' : '') + month;
  console.log(output);
 return output;
}


function currentDate(){

	var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = d.getFullYear() + '-' +
 (month<10 ? '0' : '') + month + '-' +
 (day<10 ? '0' : '') + day;

 return output;
}


function firstDayOfMonth(){

  var d = new Date();
  var month = d.getMonth()+1;
  var day = d.getDate();

  var output = d.getFullYear() + '-' +
  (month<10 ? '0' : '') + month + '-01';

  return output;
}



function toggleFullScreen(elem) {

    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
      if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (elem.requestFullScreen) {
          elem.requestFullScreen();
        } else if (elem.mozRequestFullScreen) {
          elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
          elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (elem.msRequestFullscreen) {
          elem.msRequestFullscreen();
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
      }
    }


    $(document).ready(function(){
  var date_input=$('input[id="date"]'); //our date input has the name "date"
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  var options={
    format: 'yyyy-mm-dd',
    container: container,
    todayHighlight: true,
    autoclose: true,
  };
  date_input.datepicker(options);
});


    var waitingDialog = waitingDialog || (function ($) {
      'use strict';

  // Creating modal dialog's DOM
  var $dialog = $(
    '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
    '<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
    '<div class="modal-body">' +
    '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
    '</div>' +
    '</div></div></div>');

  return {
    /**
     * Opens our dialog
     * @param message Custom message
     * @param options Custom options:
     *          options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
     *          options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
     */
     show: function (message, options) {
      // Assigning defaults
      if (typeof options === 'undefined') {
        options = {};
      }
      if (typeof message === 'undefined') {
        message = 'Loading';
      }
      var settings = $.extend({
        dialogSize: 'm',
        progressType: '',
        onHide: null // This callback runs after the dialog was hidden
      }, options);

      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('h3').text(message);
      // Adding callbacks
      if (typeof settings.onHide === 'function') {
        $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
          settings.onHide.call($dialog);
        });
      }
      // Opening dialog
      $dialog.modal();
    },
    /**
     * Closes dialog
     */
     hide: function () {
      $dialog.modal('hide');
    }
  };

})(jQuery); 