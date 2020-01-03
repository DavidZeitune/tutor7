<?php
/**
 * CityWindow Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CityWindow extends TWindow
{
    protected $form; // form
    
    // trait with onSave, onClear, onEdit
    use Adianti\Base\AdiantiStandardFormTrait;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        parent::setModal(true);
        parent::removePadding();
        parent::setSize(600,null);
        parent::setTitle('City');
        
        $this->setDatabase('samples');    // defines the database
        $this->setActiveRecord('City');   // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_City');
        
        // create the form fields
        $id       = new THidden('id');
        $name     = new TEntry('name');
        $state_id = new TDBCombo('state_id', 'samples', 'State', 'id', 'name');
        $id->setEditable(FALSE);
        
        // add the form fields
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Name', 'red')], [$name] );
        $this->form->addFields( [new TLabel('State', 'red')], [$state_id] );
        
        $name->addValidation( 'Name', new TRequiredValidator);
        $state_id->addValidation( 'State', new TRequiredValidator);
        
        // define the form action
        $this->form->addAction('Save', new TAction(array($this, 'onSave')), 'fa:save green');
        
        $this->setAfterSaveAction( new TAction([$this, 'onClose']));
        $this->setUseMessages(false);
        
        parent::add($this->form);
    }
    
    /**
     * Close window after insert
     */
    public static function onClose()
    {
        parent::closeWindow();
    }
}
