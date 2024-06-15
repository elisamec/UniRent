<?php /* Smarty version 2.6.13, created on 2010-05-28 08:07:14
         compiled from public_info.tpl */ ?>
<html>
<head>
<title>Parte pubblica</title>
</head>
<body>

<?php if ($this->_tpl_vars['identificato'] == true): ?>
   Benvenuto <b> <?php echo $this->_tpl_vars['nome']; ?>
 </b> nella parte pubblica <br>
<?php else: ?>
   Benvenuto nella parte pubblica del nostro sito <br>   
<?php endif; ?>


Queste sono le informazioni rilevanti:
<ul>
   <li> Info pubblica 1 </li>
   <li> Info pubblica 2 </li>
   <li> .... </li>
</ul>
<hr>

<?php if ($this->_tpl_vars['identificato'] == true): ?>
  <br>Vai alla sezione <a href="private_info.php"> privata </a>
  <br>Vai alla sezione <a href="public_info.php"> pubblica </a>
  <br><a href="logout.php">logout</a>
<?php else: ?>
   <br><br><a href="index.php">home page</a>   
<?php endif; ?>

</body>
</html>