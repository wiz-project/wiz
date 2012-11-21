<?php

class LangController extends Controller
{
	
	/**
	 * change language
	 */
	public function actionChangeLang($lang,$redirect) 
	{
		Yii::app()->user->setState('applicationLanguage', $lang);
		if ($lang==='it')
			setlocale(LC_ALL, "it_IT");
		elseif ($lang==='en')
			setlocale(LC_ALL, "en_US");		
		$this->redirect($redirect);
    }
}

?>