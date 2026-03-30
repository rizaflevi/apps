<br>
<br>

<button id="scanButton">TAP KARTU</button>
<br>
<div id="msg" style="border:1px solid green;height:25px"></div>

<br>
<div id="result" style="border:1px solid black;height:100px;">

</div>

<script>
if (!("NDEFReader" in window))
  alert("Web NFC is not available. Use Chrome on Android.");
  scanButton.addEventListener("click", async () => {
  //alert("User clicked scan button");

  try {
    const ndef = new NDEFReader();
    await ndef.scan();
    //alert("> Tap Kartu");
    document.getElementById("msg").innerHTML = "TAP KARTU";

    ndef.addEventListener("readingerror", () => {
        alert("Argh! Cannot read data from the NFC tag. Try another one?");
    });

    ndef.addEventListener("reading", ({ message, serialNumber }) => {
        //alert(`> Serial Number: ${serialNumber}`);
        
        var http = new XMLHttpRequest();
        var url = 'nfc.php';
        var params = `serial=${serialNumber}`;
        http.open('POST', url, true);

        //Send the proper header information along with the request
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                document.getElementById("result").innerHTML = http.responseText;
                document.getElementById("msg").innerHTML = "Hasil pembacaan kartu";
            }
        }
        http.send(params);

    });
  } catch (error) {
    alert("Argh! " + error);
  }
});
</script>

<?php

echo '<br>ini dari server';
?>

