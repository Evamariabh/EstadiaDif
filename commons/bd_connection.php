<?php
$host = 'localhost';
$user = 'root';
$passwd = '';
$schema = 'dif_san_felipe';
$pdotemp = NULL;
$dsn = 'mysql:host=' . $host . ';dbname=' . $schema. ';charset=UTF8';
$isDBConnected = false;
$DBConnError = '';
try
{
   $pdotemp = new PDO($dsn, $user,  $passwd);
   $pdotemp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $isDBConnected = true;
}
catch (PDOException $e)
{
   $DBConnError = 'Error fatal.';
}
$pdo = $pdotemp;
unset($pdotemp);
?>