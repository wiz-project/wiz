<?php

$link = Yii::app()->createAbsoluteUrl('users/approveViaLink', array('link' => '-link'));

$messages = array(
	'create' => array(
		'owner' => array(
			'subject' => 'Benvenuto in WIZ!',
			'description' => 'Gentile utente, <br/> grazie per il tuo interessamento a WIZ. <br/>
								Se ti sei registrato come "cittadino" il tuo account è già attivo e potrai visualizzare
								le informazioni relative alla risorsa idrica o esprimere un tuo parere sulla qualità del servizio.<br/>
								Se, invece, ti sei registrato come "pianificatore" il tuo account deve essere preventivamente attivato.
								Puoi comunque accedere al portale, ma le funzionalità alle quali potrai accedere sono limitate.
								Ti avvertiremo non appena il tuo account verrà attivato.
								<br/><br/>Grazie!'		
		),
		'sys_admin' => array(
			'subject' => 'Nuova Registrazione',
			'description' => 'L\'utente "$first_name $last_name" [ $username ] si è registrato con il ruolo di "pianificatore". <br/>
								Devi attivare l\'account prima che l\'utente possa effettuare le operazioni sul portale.
								<br/><br/>
								Per attivare l\'account puoi utilizzare il seguente link <a href="'.$link.'">'.$link.'</a>
								oppure accedere al portale per visualizzare il profilo dell\'utente al link
								<a href="$link_view">$link_view</a>.'
										
		),
	),
	'approve' => array(
		'owner' => array(
			'subject' => 'Attivazione account',
			'description' => 'Gentile utente, <br/> il tuo account è stato attivato.<br/>
								Adesso puoi accedere al portale e sfruttarne al meglio le potenzialità.
								<br/><br/>Grazie!'		
		),
	),
	
	'retrieve' => array(
		'owner' => array(
			'subject' => 'Nuova Password',
			'description' => 'Gentile utente, <br/>
								la tua nuova password è: $other. <br/>
								Per motivi di sicurezza di preghiamo di modificarla al prossimo accesso.
								<br/><br/>Grazie!'		
		),
	),
	
);

/*
	$description['create'] = 'New user registration: $username';
	$mail['create'] = 'For the attention of System Administrator,<br><br> the new user $first_name $last_name has logged into the system .<br> Click on the link <a href="$link_view">$link_view</a> to approve the associated role.<br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';
	
	$description['retrieve'] = 'Retrieve password: $username';
	$mail['retrieve'] = 'For the attention of $first_name $last_name,<br><br> the system, following a request received, has generated a new password to access on WIZ.<br> The new password to be used is:<br> &nbsp;&nbsp;<b>$other</b><br><br> THIS IS AN AUTOMATIC MESSAGE, PLEASE DO NOT RESPOND';

*/

?>