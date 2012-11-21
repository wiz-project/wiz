<?php
$this->pageTitle=Yii::app()->name . ' - Download Codice Sorgente';
$this->breadcrumbs=array(
	'Codice Sorgente',
);
?>
<h1>Download del Codice Sorgente</h1>

<div>

	<div class="disclaimer" id="disclaimer">
		<h2>Disclaimer</h2>
		<p>
			WIZ - Copyright (C) 2012
		</p>
		<p>
			Salvo diverso accordo tra le parti per iscritto e di NELLA MISURA MASSIMA CONSENTITA DALLA LEGGE,
			il lavoro è offerto "COSI COM'E" e non viene fornita alcuna DICHIARAZIONE O GARANZIA di qualsiasi tipo, IMPLICITA o ESPLICITA,
			LEGALE o di ALTRO TIPO, incluse, senza limitazione, le garanzie di titolo, di commerciabilità,
			di idoneità per uno scopo particolare, di non violazione dei diritti altrui, oppure l'assenza di difetti e/o errori.
			<br/>
			Alcune giurisdizioni non consentono l'esclusione di garanzie implicite, quindi questa esclusione potrebbe non essere applicabile.
			<br/><br/>
			Per maggiori informazioni fare riferimento alla licenza
			<a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
				Creative Commons Attribuzione - Non commerciale - Condividi allo stesso modo 3.0 Unported.
			</a>
			
		</p>
		
		<p>
			<div>
			<?php
				echo CHtml::link(	CHtml::image("images/zip_icon.png").'<br/> Scarica Zip',
									Yii::app()->baseUrl.Yii::app()->params['src_download_folder'].Yii::app()->params['src_zip_file'],
									array('class'=>'source-link'));
				
				echo CHtml::link(	CHtml::image("images/tar_icon.png").'<br/> Scarica Tar',
									Yii::app()->baseUrl.Yii::app()->params['src_download_folder'].Yii::app()->params['src_tar_file'],
									array('class'=>'source-link'));
			?>
			</div>
		</p>
		
	</div>
	
</div>

