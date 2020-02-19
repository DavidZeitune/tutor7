<?php
/**
 * KanbanStageForm
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class KanbanStageForm extends TWindow
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
        parent::setSize(400, null);
        parent::removePadding();
        parent::setTitle('Kanban Stage');
        
        $this->setDatabase('samples');    // defines the database
        $this->setActiveRecord('KanbanStage');   // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_City');
        
        // create the form fields
        $id          = new THidden('id');
        $title       = new TEntry('title');
        $stage_order = new THidden('stage_order');
        $id->setEditable(FALSE);
        
        // add the form fields
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Title', 'red')], [$title] );
        $this->form->addFields( [$stage_order] );
        
        // define the form action
        $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:save green');
        
        $this->setAfterSaveAction( new TAction( ['KanbanDatabaseView', 'onLoad'] ) );
        $this->setUseMessages(FALSE);
        
        TScript::create('$("body").trigger("click")');
        TScript::create('$("[name=title]").focus()');
        
        parent::add($this->form);
    }
}
