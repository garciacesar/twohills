<?php
require_once 'Crud.php';
/**
 * Class to account requests from new users
 */
class Request extends Crud {

  function __construct(){
    if (isset($_POST['request'])) {
      $email = $_POST['email'];
      $this->registered();
      $this->register($email);
    } else {
      $this->message();
    }
  }

  private function register($email){
    $verify = "SELECT id FROM guests WHERE email = :email";
    $stmt_ver = DB::prepare($verify);
    $stmt_ver->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt_ver->execute();
    $count = $stmt_ver->rowCount();
    if ($count == 0) {
      $sql = "INSERT INTO guests (`email`, `date`, `registration_ip`) VALUES (:email, NOW(), :registration_ip)";
      $stmt = DB::prepare($sql);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':registration_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
      $stmt->execute();
      $this->welcomeEmail($email);
    }
  }

  private function message(){
    echo '<div class="message" >
            <span id="caption"></span>
            <span id="cursor">|</span>
          </div>';
    echo '<div class="form">
          <form method="post">
            <input type="text" class="request" name="email" placeholder="email" autofocus required>
            <input type="submit" name="request" hidden>
          </form>
        </div>';
  }

  private function registered(){
    echo '<div class="message">
            <p>Obrigado por se registrar!!</p>
            <p>Seu email está em nosso sistema.</p>
            <p>Entraremos em contato em breve.</p>
            <p>Fique ligado em seu email =) <span id="cursor">|</span></p>
          </div>';
  }

  private function welcomeEmail($email){
    $to = "$email";
    $headers = "From: no-reply@twohills.tv";
    $subject = "Bem Vindo a TwoHills TV";
    $txt = "Olá, meu nome é César CEO da TwoHills TV.

    Eu e a equipe TwoHills estamos honrados em receber sua solicitação para participar de nossa plataforma.
    Em alguns dias um email será enviado com as instruções para criar sua conta exclusiva!!

    Não enviaremos nenhuma solicitação sobre valores ou pagamentos, caso seja necessário, será solicitado pela plataforma no momento exato!";

    mail($to, $subject, $txt, $headers);
  }

}
