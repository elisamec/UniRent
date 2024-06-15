<?php /* Smarty version 2.6.13, created on 2010-05-28 07:58:22
         compiled from verifica_accesso.tpl */ ?>
<html>
<head>
<title>Esito dell'accesso</title>
</head>
<body>

<?php if ($this->_tpl_vars['identificato'] == true): ?>
	Benvenuto <b> <?php echo $this->_tpl_vars['nome']; ?>
 </b> 
	<hr>
	<br>
	Vai alla sezione <a href="private_info.php"> privata </a><br>
	Vai alla sezione <a href="public_info.php"> pubblica </a><br>
	<a href="logout.php">logout</a>	
<?php else: ?>
    <H1><B>Utente non autorizzato</B></H1>
	<a href="index.php">torna alla home page</a>  
<?php endif; ?>



</body>
</html>