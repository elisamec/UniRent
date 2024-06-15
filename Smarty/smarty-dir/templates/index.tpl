<html>
<head>
<title> Benvenuti nel CD shop </title>
<center><h1> CD shopping </h1></center>
<BR>
</head>
<body>

<table cellpadding=5 cellspacing=3 border=1>
<tr><th>titolo</th><th>artista</th><th>prezzo</th><th>richieste</th></tr>

{section name=nr loop=$results}	
  <tr>
	<form name="add" METHOD="post" ACTION="aggiungi.php">
	
	  <td> {$results[nr].titolo}  </td>
	  <td> {$results[nr].artista} </td>
	  <td> {$results[nr].prezzo}  </td>		
	  <td> <INPUT TYPE="text" NAME="scelta" value="1" size="4" MAXLENGTH="2"> </td>
	  <td> 	<INPUT TYPE="hidden" NAME="codice"  VALUE="{$results[nr].codice}">
            <INPUT TYPE="hidden" NAME="titolo"  VALUE="{$results[nr].titolo}">
            <INPUT TYPE="hidden" NAME="artista" VALUE="{$results[nr].artista}">
	        <INPUT TYPE="hidden" NAME="prezzo"  VALUE="{$results[nr].prezzo}">
	        <INPUT TYPE="submit" NAME="submit" value="aggiungi"> </td>
	</form>
   </tr>
{sectionelse} 
   <tr>
      <td align="center"> <b> nessun risultato </b> <td>
   </tr>
{/section} 

</table>
<hr>

{if $carrello_esiste == 1}	

   {if $quantita_totale >0}
   
		Il carrello contiene <B> {$quantita_totale} </B> cd &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="svuota.php">svuota il carrello</a> <br>
		<table cellpadding=5 cellspacing=3 border=1>
		  <tr>
			<th>titolo</th><th>artista</th><th>prezzo</th><th>nel carrello</th><th>da togliere</th>
		  </tr>
			
		{section name=nr1 loop=$carrello}
		   <tr>
			  <form name="remove" METHOD="post" ACTION="rimuovi.php">
			   <td> {$carrello[nr1].titolo} </td>
			   <td> {$carrello[nr1].artista} </td>
			   <td> {$carrello[nr1].prezzo} </td>
			   <td> {$carrello[nr1].quantita} </td>
			   <td> <input name="cod" type=hidden value="{$carrello[nr1].codice}" >
					<INPUT TYPE="hidden" NAME="tit" VALUE="{$carrello[nr1].titolo}">
					<INPUT TYPE="hidden" NAME="art" VALUE="{$carrello[nr1].artista}">
					<INPUT TYPE="hidden" NAME="prez" VALUE="{$carrello[nr1].prezzo}">
					<input name="scelt" type="text" value=1 size=8 maxlength=2> </td>
				<td> <input type="submit" name="rimuovi" value="rimuovi"> </td>
			  </form>
		   </tr> 
		{/section}
		</table>		
		
		<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    
   {else}
		<b>Il carrello esiste, ma &egrave; vuoto</b>
   {/if}
{else}
     <b>Il carrello &egrave; vuoto</b>	
{/if}


</body>
</html>

