function Disp_Member(pkode_member) {
	var btn_stat = document.getElementById("SAVE_ADD");  // the submit button
    if (pkode_member == "") {
        document.getElementById("ADD_KD_MMBR").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("f_NM_MMBR").innerHTML = xmlhttp.responseText;
//				alert(xmlhttp.responseText);
				document.getElementById("f_NM_MMBR").value = xmlhttp.responseText;
            }
			if (document.getElementById("f_NM_MMBR").value == "") {
				document.getElementById("SAVE_ADD").setAttribute('disabled', 'disabled');
			} else {
				document.getElementById("SAVE_ADD").removeAttribute('disabled');
			}
        };
//		alert(btn_stat);
        xmlhttp.open("GET","kop_cek_member.php?ADD_KD_MMBR="+pkode_member,true);
        xmlhttp.send();
		
    }
}

