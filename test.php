<?
session_start();
?>
<script>
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(getpos);
  } else {
  }
function getpos(position) {
           latx=position.coords.latitude;
           lonx=position.coords.longitude;
         // Show Lat and Lon 
           document.write('<div>Lat: '+latx+'<br> Long: '+lonx+'</div>');
         // or send them to use elsewhere
         location.href = 'user_loc.php?_la='+latx+'&_lo='+lonx;
}
</script>
