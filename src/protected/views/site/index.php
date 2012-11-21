<?php $this->pageTitle=Yii::app()->name; ?>

<?php if(Yii::app()->user->isPlanner): ?>
<!-- Planner -->
<h1>Benvenuti in <i>WIZ4Planners!</i></h1>
<p>
	Questa &egrave; la sezione riservata a tutti i soggetti coinvolti nel processo di <b>pianificazione territoriale</b>.
	La pianificazione urbanistica, infatti, &egrave; strettamente legata, tra le altre cose, alla <b>disponibilit&agrave; e accessibilit&agrave; attuale
	e futura di acqua potabile</b>, tenuto conto dei problemi di scarsit&agrave;, investimenti infrastrutturali e relativi costi 
	che ne caratterizzano l'erogazione; tutto questo in un contesto di costante adattamento agli effetti globali del cambiamento 
	climatico, che influenzano inevitabilmente gli scenari futuri del territorio. <br/>
	WIZ4Planners rappresenta uno strumento di guida 
	nelle scelte di pianificazione territoriale, sia dal punto di vista politico sia dal punto di vista più strettamente tecnico, 
	consentendo  una condivisione di informazioni tra il gestore della risorsa idrica e sindaci, tecnici, professionisti, 
	permettendo l'assunzione di <b>decisioni informate</b>. &Egrave; possibile sottomettere una richiesta al gestore, 
	la <b><i>Richiesta di Risorsa Idrica</i></b>, attraverso le funzionalit&agrave; messe a disposizione della piattaforma. 
</p>
<p>	
	Sono previste tre procedure , distinte tra <b>Fase preliminare</b> (o <b>Fase 1</b>), <b>Fase attuativa</b> (o <b>Fase 2</b>) e <b>Fase esecutiva</b> (o <b>Fase 3</b>). 
	Le tre procedure non sono soggette ad obblighi di sequenzialit&agrave;, incorporando funzioni
	tra loro concettualmente diverse.
</p>

<h3>Come si usa</h3>
<p>
	L'utilizzo del sistema &egrave; molto semplice. <br/>
	Nella schermata raggiungibile da <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/water_request.png"/>,
	l'operatore &egrave; in grado di vedere tutte le sue <i>Richieste di Risorsa Idrica</i> create, in qualsiasi stato. <br/>
	Per creare una nuova richiesta &egrave; necessario cliccare su
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/water_request.png"/>, in alto a sinistra nella schermata e successivamente
	su <?php echo CHtml::link('Crea una nuova richiesta di risorsa idrica',CController::createUrl('waterRequests/create'));?>. <br/>
	Da qui &egrave; possibile inserire la richiesta con due modalit&agrave;:
	<ul>
		<li><b>Disegnare sulla mappa</b></li>
		<li><b>Caricare uno shape</b></li>
	</ul>
	A seconda della destinaione d'uso, dovranno essere compilati determinati parametri, necessari per il calcolo degli abitanti equivalenti
	e, quindi, del consumo di risorsa idrica. &Egrave; inoltre obbligatorio dare un nome al progetto generato. <br/>
	All'inoltro della <i>Richiesta di Risorsa Idrica</i>, compariranno i risultati dell'interrogazione:
	<ul>
		<li>la quantit&agrave; di risorsa idrica necessaria per la realizzazione di quel progetto</li>
		<li>la disponibilit&agrave; idrica totale</li>
		<li>le previsioni sulla risorsa al 2030, al 2060 e al 2090</li>
	</ul> 
	<br/>
	
	<b>Se la risorsa &egrave; sufficiente, i risultati saranno evidenziati in verde; in caso contrario, lo sfondo ai dati sar&agrave; di colore rosso</b>.
	Questo non implica l'impossibilit&agrave; di procedere all'implementazione; tuttavia, l'intera procedura sar&agrave;, in un certo senso,
	“etichettata” da questo risultato.
	<!-- Infine, l'operatore viene messo al corrente dell'eventuale esistenza di altre richieste inoltrate per quella stessa area. -->
</p>


<?php endif; ?>

<?php if( (Yii::app()->user->isWRUT) || (Yii::app()->user->isWRUA)): ?>
<!-- WRUT -->
<h1>Benvenuti in <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<p>
    Nella schermata raggiungibile da <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/water_request.png"/>,
	&egrave; possibile visualizzare tutte le <i>Richieste di Risorsa Idrica</i> che sono state sottomesse. <br/>
	Cliccando su ogni singola <i>Richieste di Risorsa Idrica</i>, &egrave; possibile <i>Approvare</i> o <i>Rigettare</i> la richiesta.
	Il sistema permette anche di generare un file <b>EPANET</b> associato ad ogni <i>Richieste di Risorsa Idrica</i>, cliccando sull'icona
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/document_epanet.png"/>.
	<br/>
	Sono disponibili anche funzionalità che permettono di modificare la lista delle destinazioni d'uso
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/zones.png"/>, i parametri utilizzati per il calcolo degli abitanti
	equivalenti <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/parameters.png"/> e le formule per il calcolo dell'idroesigenza
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/formulas.png"/>.
	
</p>
<?php endif; ?>

<!-- Citizen -->
<?php if(Yii::app()->user->isCitizen): ?>
<h1>Benvenuti in <i>WIZ4All!</i></h1>
<p><b>Da dove viene la nostra acqua?<br/>Quanta ne usiamo rispetto alla quantit&agrave; massima disponibile?<br/>Questo ha degli effetti
sulle nostre vite?<br/>Cosa dovremmo aspettarci in futuro?</b></p>
<p>In questa sezione, le vostre domande potranno trovare una risposta.</p>
<p>La piattaforma <b>WIZ4All</b> rende infatti disponibili una serie di informazioni sulla risorsa idrica &ndash; di solito difficili da
reperire &ndash; con l'obiettivo di diffonderne la conoscenza il pi&ugrave; possibile, cos&igrave; che la reale considerazione delle questioni ad
essa relative possa assumere una maggiore importanza nelle nostre scelte di vita.</p>
<p>Con una semplice <i>richiesta al sistema</i>, avrete la possibilit&agrave; di conoscere, per una determinata zona
territoriale:</p>
<ul>
	<li><p><span style="font-weight: normal">la </span><b>disponibilit&agrave;	di risorsa idrica</b>, in termini di capacit&agrave; della rete di
	distribuzione, nel presente e in scenari futuri</p></li>
	<li><p><span style="font-weight: normal">i </span><b>soggetti che si occupano della fornitura di acqua,</b> e i relativi costi</p></li>
	<li><p>la <b>fonte</b> da cui proviene l'acqua potabile distribuita</p></li>
	<li><p>il <b>percorso dell'acqua, dalla fonte al &ldquo;rubinetto&rdquo;</b>, ed ovviamente l'ubicazione e le caratteristiche degli impianti che
	lo rendono possibile (serbatoi, potabilizzazione, pompaggio, ecc.)</p></li>
	<li><p>le <b>caratteristiche della rete</b>: tecniche (diametro,
	materiale, ecc.) e di servizio (rotture, perdite, ecc.)</p></li>
	<li><p>il <b>costo stimato (economico e ambientale)</b> dei servizi	di trasporto, potabilizzazione,  distribuzione dell&rsquo;acqua,
	nel presente e in scenari futuri</p></li>
	<li><p>i parametri di <b>qualit&agrave; dell&rsquo;acqua</b>: sia quelli percepiti dall&rsquo;utente sia quelli misurati, in modo
	specifico, durante il processo di distribuzione (alle fonti, presso	stazioni di potabilizzazione, ecc.)</p></li>
</ul>
<p>Grazie a WIZ4All avete anche la possibilit&agrave; &ndash; e opportunit&agrave; &ndash; di dare un vostro importante contributo al raggiungimento di una sempre pi&ugrave; corretta ed efficiente
gestione dell'acqua potabile, aiutandoci nella <b>rilevazione delle sue caratteristiche qualitative</b>. 
</p>
<p>Se desiderate segnalarci la<span style="font-weight: normal">
qualit&agrave; da voi percepita dell'acqua potabile erogata, </span><b><?php echo CHtml::link('seguite il link', CController::createUrl('waterQualityOpinions/index',array())); ?>.</b></p>
<?php endif; ?>

<?php if(Yii::app()->user->isGuest): ?>
<!-- Guest -->
<h1>Benvenuti in <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<!-- 
<p>
<b>WIZ - WaterIZe spatial planning:</b> encompass future drinkwater management conditions to adapt
to climate change<br/> (<b>"Acquifichiamo" la pianificazione territoriale:</b> includere le condizioni future di
gestione dell'acqua potabile per adattarsi al cambiamento climatico)
</p>
-->
<!--
<p>
<b>WIZ - WaterIZe spatial planning ("Acquifichiamo" la pianificazione territoriale):</b>
includere le condizioni future di
gestione dell'acqua potabile per adattarsi al cambiamento climatico)
</p>

<p>
WIZ &egrave; un grande progetto ambientale cofinanziato dal Programma LIFE+ dell'Unione Europea.<br/>I
partner che realizzano il progetto sono: <b>Acque Spa</b> (Pisa, IT), Beneficiario coordinatore; <b>Autorit&agrave;
di Bacino del Fiume Arno</b> (Firenze, IT), <b>Ingegnerie Toscane Srl </b>(Firenze, IT), <b>Fundaci&oacute;n
Instituto Tecnol&oacute;gico de Galicia </b>(A Coru&ntilde;a, ES).
</p>

<p>
L'obiettivo generale di WIZ &egrave; l'integrazione di concetti e procedure per la protezione e gestione
sostenibile dell'acqua nei processi di pianificazione urbanistica e dell'ambiente edificato in generale,
tenendo conto degli impatti del cambiamento climatico. Questa piattaforma sar&agrave; in grado di
restituire rilevanti informazioni alle autorit&agrave; locali coinvolte nei processi di decision-making, in
modo <b>da garantire, all'interno della pianificazione territoriale, l'assunzione di decisioni
"informate"</b>.
</p>
<p>
WIZ mira, inoltre, a diffondere tra i cittadini la percezione della necessit&agrave; di tener conto delle
condizioni e disponibilit&agrave; futura di acqua nelle loro scelte di vita. Per questo motivo la piattaforma
mira anche ad una <b>"gestione partecipata" dell'acqua da parte dei cittadini stessi</b>, grazie a tutta
una serie di informazioni, solitamente di difficile reperibilit&agrave;, che sono messe a disposizione del
singolo utente che effettuer&agrave; una richiesta al sistema. I dati immessi dai cittadini, inoltre, potranno
contribuire ad aumentare la base di conoscenza sulle condizioni idriche del territorio, consentendo
una maggiore precisione ed attendibilit&agrave; delle risposte.
</p>
-->
<p>
La piattaforma WIZ è stata realizzata all'interno dell'omonimo progetto comunitario LIFE+ -
<b><a href="http://www.wiz-life.eu" target="_blank">WIZ: WaterIZe spatial planning</a></b>
(<i>"Acquifichiamo" la pianificazione territoriale: includere le condizioni future di gestione dell'acqua potabile per adattarsi al
cambiamento climatico</i>): il progetto è co-finanziato dalla Comunità Europea e portato avanti da Acque Spa, l'Autorità di Bacino
del Fiume Arno, Ingegnerie Toscane Srl, e il partner spagnolo Fundación Instituto Tecnológico de Galicia.
<br/><br/>
La piattaforma WIZ comprende due servizi:
<?php if(Yii::app()->user->isGuest): ?>
<a href=<?php echo $this->createUrl('waterInfo/index') ?>>WIZ4ALL</a>
<?php
endif;
?>
e
<?php if(Yii::app()->user->isGuest): ?>
<a href=<?php echo $this->createUrl('site/login') ?>>WIZ4PLANNERS</a>.
<?php
endif;
?> 

<br/><br/>
<a href=<?php echo $this->createUrl('waterInfo/index') ?>><img src="images/wiz4all.png"/></a>
Mira a diffondere tra cittadini e imprese (ma anche professionisti e esperti del settore) la percezione della necessità di tener conto
delle condizioni e disponibilità futura di acqua potabile nelle loro scelte di vita: mette infatti a loro disposizione una serie di
informazioni, solitamente di difficile reperibilità (disponibilità di risorsa, fonti d’acqua e molto altro). Solo così sarà possibile
una <b><i>"gestione partecipata"</i></b> dell'acqua da parte dei cittadini stessi, grazie anche alla possibilità di inserire una serie
di dati che vanno ad aumentare la base di conoscenza comune sulla situazione idrica del territorio. <b>L'accesso è pubblico</b>.


<br/><br/>
<a href=<?php echo $this->createUrl('site/login') ?>><img src="images/wiz4planner.png"/></a>
È in grado di fornire rilevanti informazioni alle autorità locali coinvolte nei processi di <b>pianificazione territoriale</b>,
rappresentando uno strumento di guida nelle loro scelte che mira a garantire l'assunzione di <b><i>decisioni "informate"</i></b>. 
L'obiettivo è infatti quello di integrare concetti e procedure per la protezione e gestione sostenibile dell'acqua nei processi di
pianificazione urbanistica e dell'ambiente edificato in generale, tenendo conto degli impatti del cambiamento climatico.
<b>L'accesso è riservato agli utenti autorizzati</b> (per richieste e informazioni contattare 
<a href="mailto:wiz@wiz-life.eu">wiz@wiz-life.eu</a>).
<?php if(Yii::app()->user->isGuest): ?>
Per accedere clicca <a href=<?php echo $this->createUrl('site/login') ?>>qui</a>.
<?php
endif;
?>
</p>



<?php endif; ?>

<?php if(Yii::app()->user->isGuest) { ?>
<p>
<!-- Per accedere al sistema, segui il link di <a href=<?php echo $this->createUrl('site/login') ?>>Login</a>. -->
</p>
<?php } else { 
		$user = Users::model()->findByPk(Yii::app()->user->id);
		if(!$user->approved) { ?>
			<div class="flash-notice">
				<?php echo Yii::t('user','Your registration has not yet been approved by the System Administrator.<br/> Your role it\'s the same of a <i>citizen</i> until the approval.'); ?>
			</div>
<?php   }
	  } ?> 