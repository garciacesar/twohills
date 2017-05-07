<?php
    require_once "classes/Registration.php";
    $registration = new Registration();

    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
        $email = $_POST['email'];
    } else {
       $email = "";
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Register Test</title>
    <link rel="stylesheet" href="css/hills.style.css">
  </head>
  <body>
    <?php if (!$registration->registration_successful && !$registration->verification_successful) { ?>
      <form class="regForm" action="" method="post">
        <input type="text" class="regInput" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email" required>
        <input type="password" class="regInput" id="password" name="password" value="" placeholder="Senha" required>
        <input type="password" class="regInput" id="passwordAgain" name="repeatPass" value=""
                                    placeholder="Repetir Senha" onchange="validatePass()" required>
        <input type="submit" id="register" name="register" value="Registrar">
      </form>
    <?php } ?>

    <script type="text/javascript">
      function validatePass(){
        var password = document.getElementById('password');
        var passwordAgain = document.getElementById('passwordAgain');
        var register = document.getElementById('register');
        var email = document.getElementById('email');

        if (password.value == passwordAgain.value) {
          password.style.borderColor = "green";
          passwordAgain.style.borderColor = "green";
          register.disabled = false;
        } else {
          password.style.borderColor = "red";
          passwordAgain.style.borderColor = "red";
          register.disabled = true;
        }
      }
    </script>

  </body>
</html>
