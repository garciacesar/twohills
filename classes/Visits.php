<?php
require_once 'Crud.php';
/**
 *
 */
class Visits extends Crud{

  public function visitors($page){
    $sql = "INSERT INTO visits (`page`, `date`, `ip`) VALUES (:page, NOW(), :ip)";
    $stmt = DB::prepare($sql);
    $stmt->bindValue(':page', $page, PDO::PARAM_STR);
    $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $stmt->execute();
  }

}
