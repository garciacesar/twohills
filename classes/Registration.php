<?php
require_once "Crud.php";
/**
 * Register new user
 */
class Registration extends Crud{

  public  $registration_successful  = false;
  public  $verification_successful  = false;
  public  $errors                   = array();
  public  $messages                 = array();


  function __construct(){
    if (isset($_POST['register'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $this->registerNewUser($email, $password);
    } else if (isset($_GET["id"]) && isset($_GET["verification_code"])) {
      $this->verifyNewUser($_GET["id"], $_GET["verification_code"]);
    }
  }

  private function registerNewUser($email, $password){
    $email = trim($email);

    $checkEmail = "SELECT email FROM users WHERE email = :email";
    $stmt = DB::prepare($checkEmail);
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $checkEmail = $stmt->fetchAll();

    if (count($checkEmail) > 0) {
      $this->errors[] = EMAIL_ALREADY_EXISTS;
    } else {
      $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
      $password_hash = password_hash($password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
      $activation_hash = sha1(uniqid(mt_rand(), true));

      $new_user = "INSERT INTO users (password_hash, email, activation_hash, registration_ip, registration_datetime) VALUES(:password_hash, :email, :activation_hash, :registration_ip, now())";
      $stmt = DB::prepare($new_user);
      $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':activation_hash', $activation_hash, PDO::PARAM_STR);
      $stmt->bindValue(':registration_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);

      if ($stmt->execute()) {
        $id = "SELECT id FROM users WHERE email = :email";
        $stmt = DB::prepare($id);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $result["id"];

        $this->sendEmail($id, $email, $activation_hash);
      } else {
        $this->errors[] = ACCOUNT_NOT_CREATED;
      }

    }
  }

  public function sendEmail($id, $email, $activation_hash){
    /*$headers = "From: no-reply@twohills.tv";
    $subject = "Bem Vindo a TwoHills TV";
    $txt = "https://twohills.tv/?id=" . urlencode($id) . "&verification_code=" . urlencode($activation_hash);

    $send = mail($email, $subject, $txt, $headers);*/

    $to      = 'cesar@twohills.com.br';
    $subject = 'the subject';
    $message = 'hello ' . $id . ' ' . $email . ' ' . $activation_hash . ' testado';
    $headers = 'From: webmaster@example.com' . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $send = mail($to, $subject, $message, $headers);

    if ($send){
        $this->messages[] = MESSAGE_VERIFICATION_MAIL_SENT;
        $this->registration_successful = true;
    } else {
        // delete this users account immediately, as we could not send a verification email
        $query_delete_user = "DELETE FROM users WHERE id=:id";
        $stmt = DB::prepare($query_delete_user);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->errors[] = MESSAGE_VERIFICATION_MAIL_ERROR;
    }
  }

  public function verifyNewUser($id, $activation_hash){

      // try to update user with specified information
      $query_update_user = "UPDATE users SET active = 1, activation_hash = NULL WHERE id = :id AND activation_hash = :activation_hash";
      $stmt = DB::prepare($query_update_user);
      $stmt->bindValue(':id', intval(trim($id)), PDO::PARAM_INT);
      $stmt->bindValue(':activation_hash', $activation_hash, PDO::PARAM_STR);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
          $this->verification_successful = true;
          $this->messages[] = MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL;
          header( "refresh:3;url=localhost/twohills.tv" );
      } else {
          $this->errors[] = MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL;
      }
  }
}
