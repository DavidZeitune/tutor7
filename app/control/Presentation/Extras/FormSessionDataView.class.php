<?php
/**
 * FormSessionDataView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormSessionDataView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new BootstrapFormBuilder;
        $this->form->style = 'min-width: 200px';
        $this->form->setProperty('style', 'margin:0; border:0');
        $form_id = $this->form->getId();
        
        // create the form fields
        $name  = new TEntry('name');
        $value = new TEntry('value');
        
        // add the fields inside the form
        $row = $this->form->addFields([new TLabel('Name')],  [$name] );
        $row->layout = ['col-sm-4', 'col-sm-8'];
        $row = $this->form->addFields([new TLabel('Value')], [$value] );
        $row->layout = ['col-sm-4', 'col-sm-8'];
        
        // define the form action 
        $this->form->addAction('Send', new TAction(array($this, 'onSend')), 'far:save green');
        
        TScript::create("$('#{$form_id}').closest('.popover-content').css('padding',0)");
        
        parent::add($this->form);
    }
    
    /**
     * Load form with session data
     */
    public function onEdit($param)
    {
        $this->form->setData( (object) TSession::getValue(__CLASS__) );
    }
    
    /**
     * Save form data into session
     */
    public static function onSend($param)
    {
        TSession::setValue(__CLASS__, $param);
        TScript::create("$('.popover').popover('hide');");
    }
}
