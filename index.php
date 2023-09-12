<!DOCTYPE html>
<html>
<head>
   <title>Пример формы</title>
</head>
<body>
   <?php
   require_once "main.php";

   $fixer = new Fixer();

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Обработка данных, если форма была отправлена
      $category = trim((string) $_POST["category"]);

      // Обработка загруженного файла, если он был загружен
      if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
         $uploadedFileName = $_FILES["file"]["name"];
         move_uploaded_file($_FILES["file"]["tmp_name"], $uploadedFileName);

         $fixer->main($uploadedFileName, $category);

         // Отправка файла пользователю на скачивание
         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename="' . basename($uploadedFileName) . '"');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
         header('Pragma: public');
         header('Content-Length: ' . filesize($uploadedFileName));
         readfile($uploadedFileName);

         // Удаление временного файла
         unlink($uploadedFileName);

         exit;
      }
   }
   ?>

   <form method="POST" action="index.php" enctype="multipart/form-data">
      <label for="radio">Выберите опцию:</label>
      <input type="radio" name="category" value="<?php echo Fixer::CATEGORY_GIRL_CLOTHES; ?>"> Женская одежда
      <input type="radio" name="category" value="<?php echo Fixer::CATEGORY_GIRL_BOOTS; ?>"> Женская обувь
      <input type="radio" name="category" value="<?php echo Fixer::CATEGORY_MAN_CLOTHES; ?>"> Мужская одежда
      <input type="radio" name="category" value="<?php echo Fixer::CATEGORY_MAN_BOOTS; ?>"> Мужская обувь

      <label for="oldCategory">Старая категория</label>
      <input type="text" name="oldCategory"><br>

      <label for="newCategory">Новая категория</label>
      <input type="text" name="newCategory"><br>

      <label for="file">Загрузить файл:</label>
      <input type="file" name="file"><br>

      <input type="submit" value="Отправить">
   </form>
</body>
</html>
