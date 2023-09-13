<!DOCTYPE html>
<html lang="ru">
<head>
   <title>Обработка данных товаров</title>
</head>
<body>
   <?php
   require_once "Domain.php";

   $domain = new Domain();

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Обработка данных, если форма была отправлена
      $category = trim((string) $_POST["category"]);

       if ($_FILES && $_FILES["file"]["error"]== UPLOAD_ERR_OK) {
           $originalName = $_FILES["file"]["name"];

           $srcFilePath =  sys_get_temp_dir() . DIRECTORY_SEPARATOR . $originalName;

           move_uploaded_file($_FILES["file"]["tmp_name"], $srcFilePath);

           $domain->main($srcFilePath, $category);
       }
   }
   ?>

   <form method="POST" action="index.php" enctype="multipart/form-data">
      <label for="radio">Выберите категорию товаров: </label>
       <div>
           <input type="radio" name="category" value="<?php echo Domain::CATEGORY_WOMEN_CLOTHES; ?>"><?php echo Domain::CATEGORY_WOMEN_CLOTHES; ?>
       </div>
       <div>
           <input type="radio" name="category" value="<?php echo Domain::CATEGORY_WOMEN_BOOTS; ?>"> <?php echo Domain::CATEGORY_WOMEN_BOOTS; ?>
       </div>
       <div>
           <input type="radio" name="category" value="<?php echo Domain::CATEGORY_MAN_CLOTHES; ?>"> <?php echo Domain::CATEGORY_MAN_CLOTHES; ?>
       </div>
       <div>
           <input type="radio" name="category" value="<?php echo Domain::CATEGORY_MAN_BOOTS; ?>"> <?php echo Domain::CATEGORY_MAN_BOOTS; ?>
       </div>

       <br>

       <div>
           <label for="file">Загрузить файл:</label>
           <input type="file" name="file"><br>
       </div>

       <br>

      <input type="submit" value="Отправить">
   </form>
</body>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    form {
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        width: 300px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="radio"] {
        margin-right: 10px;
    }

    input[type="file"] {
        margin-top: 10px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

</style>
</html>
