<?php
$this->pageTitle=Yii::app()->name . ' - Contattaci';
$this->breadcrumbs=array(
	'Contattaci',
);
?>

<h1><?php echo Yii::t('contacts','Contact Us')?></h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<div style="line-height: 25px">
	<h2>Domande?</h2>
	<p>
		Non riesci a capire come effettuare un'operazione? Ti servono dei chiarimenti?<br/>
		
		Consulta le nostre <a href="">FAQ</a>
	</p>
	
	<h2>Bug?</h2>
	<p>
		Non puoi completare il tuo lavoro perchè si è verificato un errore imprevisto? Hai notato un malfunzionamento? <br/>
		
		Utilizza il link in basso a destra 'Invia un feedback' (presente in ogni pagina). Con semplici passi
		potrai fornirci una descrizione dettagliata del problema.
	</p>
	
	<h2>Scrivici!</h2>
	<p>
		Preferisci metterti in contatto con noi?<br/>
		Inviaci un'email a <a href="mailto:acque@cpr.it">acque(at)cpr.it</a>: segnalazioni, suggerimenti, critiche sono ben accetti!
	</p>

</div>
<?php endif; ?>