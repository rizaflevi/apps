<?php
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_FILES['image'])) {
  $target_dir = "foto/"; // direktori tujuan untuk menyimpan gambar
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // periksa apakah file gambar benar-benar gambar atau bukan
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "File bukan gambar.";
    $uploadOk = 0;
  }

  // periksa ukuran file
  if ($_FILES["image"]["size"] > 500000) {
    echo "Maaf, file terlalu besar.";
    $uploadOk = 0;
  }

  // Izinkan hanya format gambar tertentu
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Maaf, hanya format JPG, JPEG, PNG & GIF yang diizinkan.";
    $uploadOk = 0;
  }

  // cek apakah terdapat kesalahan
  if ($uploadOk == 0) {
    echo "Maaf, file gagal diunggah.";
  } else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      echo "File ". htmlspecialchars( basename( $_FILES["image"]["name"])). " telah diunggah.";
    } else {
      echo "Maaf, terjadi kesalahan saat mengunggah file.";
    }
  }
}
?>

<!-- Tampilan Form Upload Gambar -->
<!DOCTYPE html>
<html>
<body>
  <form action="" method="post" enctype="multipart/form-data">
    <label for="image">Pilih gambar:</label>
    <input type="file" name="image" id="image">
    <input type="submit" value="Upload" name="submit">
  </form>
</body>
</html>
