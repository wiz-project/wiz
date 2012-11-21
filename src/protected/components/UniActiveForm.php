<?php

class UniActiveForm extends CActiveForm
{
    public $uniform = array();
 
    public function init()
    {
        $this->widget('ext.pixelmatrix.EUniform', $this->uniform);
        parent::init();
    }
}