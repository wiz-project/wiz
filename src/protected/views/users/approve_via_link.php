<div>
	<p>
		<?php if ($approved) echo 'Registrazione approvata';
				else {
					echo 'Errore!';
					echo '<br/>';
					echo $msg;	
				} 
		?>
	</p>
</div>