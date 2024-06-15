<?php /* Smarty version 2.6.13, created on 2010-05-28 08:01:48
         compiled from private_info.tpl */ ?>
<html>
<head>
<title>Parte Privata</title>
</head>
<body>

<?php if ($this->_tpl_vars['identificato'] == true): ?>
   Benvenuto <b> <?php echo $this->_tpl_vars['nome']; ?>
 </b> nella parte privata <br>
   Queste sono le informazioni rilevanti:
  <ul>
    <li> Info privata 1 </li>
    <li> Info privata 2 </li>
    <li> .... </li>
  </ul>
  <hr>

  <br>Vai alla sezione <a href="private_info.php"> privata </a>
  <br>Vai alla sezione <a href="public_info.php"> pubblica </a>
  <br><a href="logout.php">logout</a>
<?php else: ?>
   <H1><B>Accesso non autorizzato</B></H1>
   <a href="index.php">torna all'inizio</a>   
<?php endif; ?>

</body>
</html>