<?php /* Smarty version 2.6.13, created on 2010-05-28 17:40:22
         compiled from index.tpl */ ?>
<html>
<head>
<title> Benvenuti nel CD shop </title>
<center><h1> CD shopping </h1></center>
<BR>
</head>
<body>

<table cellpadding=5 cellspacing=3 border=1>
<tr><th>titolo</th><th>artista</th><th>prezzo</th><th>richieste</th></tr>

<?php unset($this->_sections['nr']);
$this->_sections['nr']['name'] = 'nr';
$this->_sections['nr']['loop'] = is_array($_loop=$this->_tpl_vars['results']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['nr']['show'] = true;
$this->_sections['nr']['max'] = $this->_sections['nr']['loop'];
$this->_sections['nr']['step'] = 1;
$this->_sections['nr']['start'] = $this->_sections['nr']['step'] > 0 ? 0 : $this->_sections['nr']['loop']-1;
if ($this->_sections['nr']['show']) {
    $this->_sections['nr']['total'] = $this->_sections['nr']['loop'];
    if ($this->_sections['nr']['total'] == 0)
        $this->_sections['nr']['show'] = false;
} else
    $this->_sections['nr']['total'] = 0;
if ($this->_sections['nr']['show']):

            for ($this->_sections['nr']['index'] = $this->_sections['nr']['start'], $this->_sections['nr']['iteration'] = 1;
                 $this->_sections['nr']['iteration'] <= $this->_sections['nr']['total'];
                 $this->_sections['nr']['index'] += $this->_sections['nr']['step'], $this->_sections['nr']['iteration']++):
$this->_sections['nr']['rownum'] = $this->_sections['nr']['iteration'];
$this->_sections['nr']['index_prev'] = $this->_sections['nr']['index'] - $this->_sections['nr']['step'];
$this->_sections['nr']['index_next'] = $this->_sections['nr']['index'] + $this->_sections['nr']['step'];
$this->_sections['nr']['first']      = ($this->_sections['nr']['iteration'] == 1);
$this->_sections['nr']['last']       = ($this->_sections['nr']['iteration'] == $this->_sections['nr']['total']);
?>	
  <tr>
	<form name="add" METHOD="post" ACTION="aggiungi.php">
	
	  <td> <?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['titolo']; ?>
  </td>
	  <td> <?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['artista']; ?>
 </td>
	  <td> <?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['prezzo']; ?>
  </td>		
	  <td> <INPUT TYPE="text" NAME="scelta" value="1" size="4" MAXLENGTH="2"> </td>
	  <td> 	<INPUT TYPE="hidden" NAME="codice"  VALUE="<?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['codice']; ?>
">
            <INPUT TYPE="hidden" NAME="titolo"  VALUE="<?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['titolo']; ?>
">
            <INPUT TYPE="hidden" NAME="artista" VALUE="<?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['artista']; ?>
">
	        <INPUT TYPE="hidden" NAME="prezzo"  VALUE="<?php echo $this->_tpl_vars['results'][$this->_sections['nr']['index']]['prezzo']; ?>
">
	        <INPUT TYPE="submit" NAME="submit" value="aggiungi"> </td>
	</form>
   </tr>
<?php endfor; else: ?> 
   <tr>
      <td align="center"> <b> nessun risultato </b> <td>
   </tr>
<?php endif; ?> 

</table>
<hr>

<?php if ($this->_tpl_vars['carrello_esiste'] == 1): ?>	

   <?php if ($this->_tpl_vars['quantita_totale'] > 0): ?>
   
		Il carrello contiene <B> <?php echo $this->_tpl_vars['quantita_totale']; ?>
 </B> cd &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="svuota.php">svuota il carrello</a> <br>
		<table cellpadding=5 cellspacing=3 border=1>
		  <tr>
			<th>titolo</th><th>artista</th><th>prezzo</th><th>nel carrello</th><th>da togliere</th>
		  </tr>
			
		<?php unset($this->_sections['nr1']);
$this->_sections['nr1']['name'] = 'nr1';
$this->_sections['nr1']['loop'] = is_array($_loop=$this->_tpl_vars['carrello']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['nr1']['show'] = true;
$this->_sections['nr1']['max'] = $this->_sections['nr1']['loop'];
$this->_sections['nr1']['step'] = 1;
$this->_sections['nr1']['start'] = $this->_sections['nr1']['step'] > 0 ? 0 : $this->_sections['nr1']['loop']-1;
if ($this->_sections['nr1']['show']) {
    $this->_sections['nr1']['total'] = $this->_sections['nr1']['loop'];
    if ($this->_sections['nr1']['total'] == 0)
        $this->_sections['nr1']['show'] = false;
} else
    $this->_sections['nr1']['total'] = 0;
if ($this->_sections['nr1']['show']):

            for ($this->_sections['nr1']['index'] = $this->_sections['nr1']['start'], $this->_sections['nr1']['iteration'] = 1;
                 $this->_sections['nr1']['iteration'] <= $this->_sections['nr1']['total'];
                 $this->_sections['nr1']['index'] += $this->_sections['nr1']['step'], $this->_sections['nr1']['iteration']++):
$this->_sections['nr1']['rownum'] = $this->_sections['nr1']['iteration'];
$this->_sections['nr1']['index_prev'] = $this->_sections['nr1']['index'] - $this->_sections['nr1']['step'];
$this->_sections['nr1']['index_next'] = $this->_sections['nr1']['index'] + $this->_sections['nr1']['step'];
$this->_sections['nr1']['first']      = ($this->_sections['nr1']['iteration'] == 1);
$this->_sections['nr1']['last']       = ($this->_sections['nr1']['iteration'] == $this->_sections['nr1']['total']);
?>
		   <tr>
			  <form name="remove" METHOD="post" ACTION="rimuovi.php">
			   <td> <?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['titolo']; ?>
 </td>
			   <td> <?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['artista']; ?>
 </td>
			   <td> <?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['prezzo']; ?>
 </td>
			   <td> <?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['quantita']; ?>
 </td>
			   <td> <input name="cod" type=hidden value="<?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['codice']; ?>
" >
					<INPUT TYPE="hidden" NAME="tit" VALUE="<?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['titolo']; ?>
">
					<INPUT TYPE="hidden" NAME="art" VALUE="<?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['artista']; ?>
">
					<INPUT TYPE="hidden" NAME="prez" VALUE="<?php echo $this->_tpl_vars['carrello'][$this->_sections['nr1']['index']]['prezzo']; ?>
">
					<input name="scelt" type="text" value=1 size=8 maxlength=2> </td>
				<td> <input type="submit" name="rimuovi" value="rimuovi"> </td>
			  </form>
		   </tr> 
		<?php endfor; endif; ?>
		</table>		
		
		<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    
   <?php else: ?>
		<b>Il carrello esiste, ma &egrave; vuoto</b>
   <?php endif; ?>
<?php else: ?>
     <b>Il carrello &egrave; vuoto</b>	
<?php endif; ?>


</body>
</html>
