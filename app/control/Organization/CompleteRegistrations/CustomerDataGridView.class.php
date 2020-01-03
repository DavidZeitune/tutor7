<?php
/**
 * CustomerDataGridView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CustomerDataGridView extends TPage
{
    private $form;      // search form
    private $datagrid;  // listing
    private $pageNavigation;
    
    use Adianti\Base\AdiantiStandardListTrait;
    
    /**
     * Class constructor
     * Creates the page, the search form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('samples'); // defines the database
        $this->setActiveRecord('Customer'); // defines the active record
        $this->setDefaultOrder('id', 'asc');  // defines the default order
        $this->addFilterField('id', '=', 'id'); // add a filter field
        $this->addFilterField('name', 'like', 'name'); // add a filter field
        $this->addFilterField('address', 'like', 'address'); // add a filter field
        $this->addFilterField('gender', '=', 'gender'); // add a filter field
        $this->addFilterField('(SELECT name from city WHERE id=customer.city_id)', 'like', 'city_name'); // add a filter field
        $this->setOrderCommand('city->name', '(select name from city where city_id = id)');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->enablePopover('Popover', 'Hi <b>{name}</b>, <br> that lives at <b>{city->name} - {city->state->name}</b>');
        
        // creates the datagrid columns
        $col_id      = new TDataGridColumn('id', 'Id', 'center', '10%');
        $col_name    = new TDataGridColumn('name', 'Name', 'left', '28%');
        $col_address = new TDataGridColumn('address', 'Address', 'left', '28%');
        $col_city    = new TDataGridColumn('{city->name} ({city->state->name})', 'City', 'left', '28%');
        $col_gender  = new TDataGridColumn('gender', 'Gender', 'left', '6%');
        
        $col_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $col_address->setAction(new TAction([$this, 'onReload']), ['order' => 'address']);
        $col_name->setAction(new TAction([$this, 'onReload']), ['order' => 'name']);
        $col_city->setAction(new TAction([$this, 'onReload']), ['order' => 'city->name']);
        
        $col_gender->setTransformer( function ($value) {
            return $value == 'F' ? 'Female' : 'Male';
        });
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_name);
        $this->datagrid->addColumn($col_address);
        $this->datagrid->addColumn($col_city);
        $this->datagrid->addColumn($col_gender);
        
        // creates two datagrid actions
        $action1 = new TDataGridAction(['CustomerFormView', 'onEdit'], ['id'=>'{id}', 'register_state' => 'false']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1, 'Edit', 'far:edit blue');
        $this->datagrid->addAction($action2 ,'Delete', 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the form
        $this->form = new TForm('form_search_customer');
        
        // add datagrid inside form
        $this->form->add($this->datagrid);
        $this->form->style = 'overflow-x:auto';
        
        // create the form fields
        $id        = new TEntry('id');
        $name      = new TEntry('name');
        $address   = new TEntry('address');
        $city_name = new TEntry('city_name');
        $gender    = new TCombo('gender');
        
        $gender->addItems( [ 'M' => 'Male', 'F' => 'Female' ] );
        
        // ENTER fires exitAction
        $id->exitOnEnter();
        $name->exitOnEnter();
        $address->exitOnEnter();
        $city_name->exitOnEnter();
        
        $id->setSize('100%');
        $name->setSize('100%');
        $address->setSize('100%');
        $city_name->setSize('100%');
        $gender->setSize('70');
        
        // avoid focus on tab
        $id->tabindex = -1;
        $name->tabindex = -1;
        $address->tabindex = -1;
        $city_name->tabindex = -1;
        $gender->tabindex = -1;
        
        $id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $name->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $address->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $city_name->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $gender->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        
        // create row with search inputs
        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);
        
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', $id));
        $tr->add( TElement::tag('td', $name));
        $tr->add( TElement::tag('td', $address));
        $tr->add( TElement::tag('td', $city_name));
        $tr->add( TElement::tag('td', $gender));
        
        $this->form->addField($id);
        $this->form->addField($name);
        $this->form->addField($address);
        $this->form->addField($city_name);
        $this->form->addField($gender);
        
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data'));
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->enableCounters();
        
        $panel = new TPanelGroup(_t('Customer list'));
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown('Export', 'fa:list');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( 'Save as CSV', new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( 'Save as PDF', new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( 'Save as XML', new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );
        
        $panel->addHeaderActionLink( 'New',  new TAction(['CustomerFormView', 'onEdit'], ['register_state' => 'false']), 'fa:plus green' );
        
        // creates the page structure using a vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);
        
        // add the box inside the page
        parent::add($vbox);
    }
}
