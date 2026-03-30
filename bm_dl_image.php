<?php
$files = glob("upload/"."*.*");
$countFiles = count($files); //Don't do calculations in your loop, this is really slow

for ($i=0; $i<$countFiles; $i++)
{
    $num = $files[$i];

    echo '<a href="download/?_file=' . $files[$i] . '"><img src="'.$num.'" alt="Here should be image" width="256" height="192" ></a><br/>';
}
?>
