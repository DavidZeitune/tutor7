<?php
/**
 * SplitLeftRegisterView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SplitLeftRegisterView extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    // trait with onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('samples');        // defines the database
        $this->setActiveRecord('City');       // defines the active record
        $this->addFilterField('name', 'like', 'name'); // filter field, operator, form field
        $this->setDefaultOrder('id', 'asc');  // define the default order
        
        // creates a new form
        $this->form = new TForm('form_standard_seek');
        
        $box = new THBox;
        //$box->{'width'} = '100%';
        $this->form->add($box);
        
        // create the form fields
        $name = new TEntry('name');
        $name->setSize('100%');
        $name->placeholder = _t('Search');
        
        // keeps the field's value
        $name->setValue( TSession::getValue('tstandardseek_display_value') );
        
        // create the action button
        $search_button = new TButton('search');
        $search_action = new TAction(array($this, 'onSearch'));
        $search_button->setAction($search_action, AdiantiCoreTranslator::translate('Search'));
        $search_button->setImage('fa:search blue');
        
        $clear_button = new TButton('clear');
        $clear_action = new TAction(array($this, 'clear'));
        $clear_button->setAction($clear_action, AdiantiCoreTranslator::translate('Clear'));
        $clear_button->setImage('fa:eraser red');
        
        // add a row for the filter field
        $box->add( $name )->style = 'width: calc(100% - 170px); float:left; text-align: center';
        $box->add( $search_button )->style='width: 80px; float:left; text-align: center';
        $box->add( $clear_button )->style='width: 80px; float:left; text-align: center';
        
        $this->form->setFields(array($name, $search_button, $clear_button));
        $this->form->setData( TSession::getValue('SplitLeftRegisterView_filter_data') );
        
        // creates the DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = "100%";
        
        // creates the datagrid columns
        $col_id    = new TDataGridColumn('id', 'Id', 'right', '10%');
        $col_name  = new TDataGridColumn('name', 'Name', 'left', '60%');
        $col_state = new TDataGridColumn('state->name', 'State', 'center', '30%');
        
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_name);
        $this->datagrid->addColumn($col_state);
        
        $col_id->setAction( new TAction([$this, 'onReload']),   ['order' => 'id']);
        $col_name->setAction( new TAction([$this, 'onReload']), ['order' => 'name']);
        
        $action1 = new TDataGridAction(['SplitRightRegisterView', 'onEdit'], ['target_container' => 'right-panel']);
        $action1->setLabel('Edit');
        $action1->setImage('far:edit blue');
        $action1->setFields(['id']);
        $this->datagrid->addAction($action1);
        
        $action2 = new TDataGridAction([$this, 'onDelete']);
        $action2->setLabel('Delete');
        $action2->setImage('far:trash-alt red');
        $action2->setFields(['id']);
        $this->datagrid->addAction($action2);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        
        // creates the page structure using a table
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add($this->form);
        $vbox->add(TPanelGroup::pack($this->form, $this->datagrid, $this->pageNavigation));
        
        // add the table inside the page
        parent::add($vbox);
    }
    
    /**
     * Clear filters
     */
    function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }
}
