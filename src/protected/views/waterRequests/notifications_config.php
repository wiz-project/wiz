<?php

$messages = array(
	'cancelled' => array(
		'owner' => array(
			'subject' => 'Cancellazione Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata cancellata.<br/>
								Ti ricordiamo che le richieste cancellate rimangono consultabili e comunque possono sempre essere
								ripristinate.
								<br/><br/>Grazie!'		
		),
	),

	'saved' => array(
		'owner' => array(
			'subject' => 'Salvataggio Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata salvata.<br/>
								Ti ricordiamo che le richieste salvate non sono visibili al gestore; puoi effettuare tutte le modifiche
								che vuoi ma quando hai completato il tuo lavoro devi "sottomettere" la richiesta affinchè
								il gestore le possa valutare.
								<br/><br/>Grazie!'		
		),
	),
	
	'submitted' => array(
		'owner' => array(
			'subject' => 'Invio Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata sottomessa.<br/>
								Il gestore visualizzerà a breve la tua richiesta e, se è in fase 2 o 3, effettuerà anche
								delle valutazioni.
								Accedendo al portale potrai, in ogni momento, monitorare l\'evoluzione della tua richiesta. 
								
								<br/><br/>Grazie!'		
		),
		'wrut' => array(
			'subject' => 'Invio Richiesta di Risorsa Idrica',
			'description' => 'L\'utente $first_name $last_name ha sottomesso una richiesta di risorsa idrica 
								<a href="$link_view">$project" [ $id ]</a>.<br/>
								Visualizzala attentamente e, se è in fase 2 o 3, effettua le tue valutazioni tecniche.'				
		),
	),
	
	'approved' => array(
		'owner' => array(
			'subject' => 'Approvazione Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata approvata dall\'ufficio tecnico del gestore.<br/>
								Tuttavia, affinchè la richiesta venga confermata, è necessaria anche l\'approvazione da parte
								dell\'ufficio amministrativo del gestore.
								Accedendo al portale potrai, in ogni momento, monitorare l\'evoluzione della tua richiesta. 
								
								<br/><br/>Grazie!'		
		),
		'wrua' => array(
			'subject' => 'Approvazione Richiesta di Risorsa Idrica',
			'description' => 'La richiesta di risorsa idrica <a href="$link_view">$project" [ $id ]</a> è stata approvata
								dall\'ufficio tecnico.<br/>
								Valuta la richiesta per decidere se confermarla o rifiutarla.'				
		),
	),
	
	'rejected' => array(
		'owner' => array(
			'subject' => 'Rifiuto Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata rifiutata.<br/>
								Accedi al portale per leggere le motivazioni ed eventualmente correte la richiesta e ri-sottometterla.
								<br/><br/>Grazie!'		
		),
		
	),
	
	'confirmed' => array(
		'owner' => array(
			'subject' => 'Conferma Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata confermata.<br/>
								Il gestore si impegnerà a garantirti quanto specificato nella tua richiesta nei modi e nei termini
								previsti specificati nella richiesta stessa.
								
								<br/><br/>Grazie!'		
		),
		
	),

	'refused' => array(
		'owner' => array(
			'subject' => 'Rifiuto Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata rifiutata.<br/>
								Accedi al portale per leggere le motivazioni ed eventualmente correte la richiesta e ri-sottometterla.
								<br/><br/>Grazie!'		
		),
		
	),

	'in_future' => array(
		'owner' => array(
			'subject' => 'Rifiuto Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata posticipata.<br/>
								Accedi al portale per leggere le motivazioni ed eventualmente correte la richiesta e ri-sottometterla.
								<br/><br/>Grazie!'		
		),
		
	),
	
	'timeout' => array(
		'owner' => array(
			'subject' => 'Scadenza Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è scaduta.<br/>
								Accedi al portale e, se sei interessato, risottometti la tua richiesta. 
								
								<br/><br/>Grazie!'		
		),
		
	),
	
	'in_progress' => array(
		'owner' => array(
			'subject' => 'Richiesta di Risorsa Idrica in lavorazione',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è in lavorazione.<br/>
								<br/><br/>Grazie!'		
		),
		
	),
	
	'completed' => array(
		'owner' => array(
			'subject' => 'Completamento Richiesta di Risorsa Idrica',
			'description' => 'Gentile $first_name $last_name,<br/><br/> la tua richiesta 
								<a href="$link_view">$project" [ $id ]</a> è stata completata.<br/>
								<br/><br/>Grazie!'		
		),
		
	),
	
);



/*
	$description['create'] = 'New Water Requests: $project';
	$mail['create'] = 'For the attention of WRU Technical Office,<br><br> a new notification has been generated by the system.<br> Connects to the site to view its contents.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['temp'] = 'Water Requests Status: Temp';
	$mail['temp'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in TEMP.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['submitted'] = 'Water Requests Status: Submitted';
	$mail['submitted'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in SUBMITTED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['saved'] = 'Water Requests Status: Saved';
	$mail['saved'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in SAVED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';

	$description['cancelled'] = 'Water Requests Status: Cancelled';
	$mail['cancelled'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in CANCELLED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';	
	$description['approved'] = 'Water Requests Status: Approved';
	$mail['approved'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in $type_status.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['rejected'] = 'Water Requests Status: Rejected';
	$mail['rejected'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in REJECTED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['in_future'] = 'Water Requests Status: In_future';
	$mail['in_future'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in IN_FUTURE.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['confirmed'] = 'Water Requests Status: Confirmed';
	$mail['confirmed'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in CONFIRMED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['refused'] = 'Water Requests Status: Refused';
	$mail['refused'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in REFUSED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['in_progress'] = 'Water Requests Status: In_progress';
	$mail['in_progress'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in IN_PROGRESS.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['timeout'] = 'Water Requests Status: Timeout';
	$mail['timeout'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in TIMEOUT.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['completed'] = 'Water Requests Status: Completed';
	$mail['completed'] = 'For the attention of WRU Technical Office,<br><br> the requests [ $id, $project ] has change status in COMPLETED.<br> Click on the link <a href="$link_view">$link_view</a> to see details.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
*/


?>