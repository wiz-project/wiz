<?php
 
/**
 * ApplicationConfigBehavior is a behavior for the application.
 * It loads additional config parameters that cannot be statically 
 * written in config/main
 */
class ApplicationConfigBehavior extends CBehavior
{
    /**
     * Declares events and the event handler methods
     * See yii documentation on behavior
     */
    public function events()
    {
        return array_merge(parent::events(), array(
            'onBeginRequest'=>'beginRequest',
        ));
    }
 
    /**
     * Load configuration that cannot be put in config/main
     */
    public function beginRequest()
    {
		if ($this->owner->user->getState('applicationLanguage')) {
			$this->owner->language=$this->owner->user->getState('applicationLanguage');
		}
		else {
			$this->owner->user->setState('applicationLanguage', Yii::app()->params['language']);
			$this->owner->language=Yii::app()->params['language'];
		}
		
	   	return;
    }
}