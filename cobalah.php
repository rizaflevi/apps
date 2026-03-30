<?php
// http://coursesweb.net/php-mysql/
// http://coursesweb.net/ajax/check-validate-input-field-loses-focus-php-ajax_t

// array with error messages for each input field
$err = array(
  'exname'=>'Not registered name.',
  'expass'=>'Incorrect password.',
  'exmail'=>'Invalid email address.'
);
$reout = '';         // data to return

// Valided values for name, password, email
$name = 'coursesweb';
$password = 'mypass';
$email = 'me@domain.com';

// validate the POST values
if(isset($_POST['exname']) && $_POST['exname'] != $name) $reout = $err['exname'];
else if(isset($_POST['expass']) && $_POST['expass'] != $password) $reout = $err['expass'];
else if(isset($_POST['exmail']) && $_POST['exmail'] != $email) $reout = $err['exmail'];

echo $reout;

?>

<form action="some_script.php" method="post">
 <label for="exname">Name: <input type="text" id="exname" name="exname" /><span class="err"></span></label>
 <div>Password: <input type="password" id="expass" name="expass" /><span class="err"></span></div>
 <label for="exmail">E-mail: <input type="text" id="exmail" name="exmail" /><span class="err"></span></label><br/>
 <input type="submit" id="exsbm" disabled="disabled" value="Send" />
</form>



<script>
// object to validate form fields when they lose focus, via Ajax
// receives the ID of the Submit button, and an Array with the IDs of the fields which to validate
var checkFormElm = function(id_sbm, fields) {
  // from: http://coursesweb.net/ajax/
  var phpcheck = 'check.php';  // Here add the php file that validate the form element
  var elm_sbm = document.getElementById(id_sbm);  // the submit button
  var fields = fields || [];  // store the fields ID
  var elm_v = {};  // store items with "elm_name:value" (to know when it is changed)
  var err = {};  // stores form elements name, with value of -1 for invalid, value 1 for valid, and 0 for not checked

  // change the css class of elm
  var setelm = function(elm, val) {
    // if val not empty, sets in err an item with element name, and value of 1
    // sets border to this form element,
    // else, sets in err an item with element name, and value of 0, and removes the border
    if(val != '') {
      err[elm.name] = -1;
      elm.className = 'errinp';
      if(elm_sbm) elm_sbm.setAttribute('disabled', 'disabled');  // disables the submit
      elm.parentNode.querySelector('.err').innerHTML = val;  //  adds the error message
    }
    else {
      err[elm.name] = 1;
      elm.className = 'vinp';
      elm.parentNode.querySelector('.err').innerHTML = '';  //  removes the error message

      // check if invalid or not checked items in $err (with value not 1)
      var inv = 0;
      for(var key in err) {
        if(err[key] != 1) {
          inv = 1;
          break;
        }
      }

      // if not invalid element, enables the submit button, and sends the form
      if(inv == 0 && elm_sbm) {
        elm_sbm.removeAttribute('disabled');
        elm_sbm.form.submit();
      }
    }
  }

  // sends data to a php file, via POST, and displays the received answer
  var checkAjax = function(elm) {
    if(elm.value != '' && (!elm_v[elm.name] || elm_v[elm.name] != elm.value)) {
      elm_v[elm.name] = elm.value;  // store name:value to know if was modified
      var xmlHttp =  (window.ActiveXObject) ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();  // gets the XMLHttpRequest instance

      // create pairs index=value with data that must be sent to server
      var  datatosend = elm.name +'='+ elm.value;
      xmlHttp.open("POST", phpcheck, true);      // set the request to php file

      // adds  a header to tell the PHP script to recognize the data as is sent via POST
      xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlHttp.send(datatosend);     // calls the send() method with datas as parameter

      // Check request status
      xmlHttp.onreadystatechange = function() {
        if(xmlHttp.readyState == 4) setelm(elm, xmlHttp.responseText);
      }
    }
    else if(elm.value =='') setelm(elm, 'This field must be completed.');
  }

  // register onchange event to form elements that must be validated with PHP via Ajax
  for(var i=0; i<fields.length; i++) {
    if(document.getElementById(fields[i])) {
      var elm = document.getElementById(fields[i]);
      err[elm.name] = 0;  //store fields-name in $err with value 0 (not checked)
      elm.addEventListener('change', function(e){ checkAjax(e.target);});
    }
  }
}

/* USAGE */
// array with IDs of the fields to validate
var fields = ['exname', 'expass', 'exmail'];

// create an object instance of checkFormElm(), passing the ID of the submit button, and the $fields
var chkF = new checkFormElm('exsbm', fields);
</script>
