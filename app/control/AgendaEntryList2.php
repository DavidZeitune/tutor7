<?php
/**
 * AgendaEntryList Listing
 * @author  <your name here>
 */
class AgendaEntryList2 extends TWindow
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('samples');            // defines the database
        $this->setActiveRecord('AgendaEntry');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('entry_date', 'like', 'entry_date'); // filterField, operator, formField
        $this->addFilterField('start_hour', 'like', 'start_hour'); // filterField, operator, formField
        $this->addFilterField('duration', 'like', 'duration'); // filterField, operator, formField
        $this->addFilterField('title', 'like', 'title'); // filterField, operator, formField
        $this->addFilterField('description', 'like', 'description'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_AgendaEntry');
        $this->form->setFormTitle('AgendaEntry');
        

        // create the form fields
        $id = new TEntry('id');
        $entry_date = new TEntry('entry_date');
        $start_hour = new TEntry('start_hour');
        $duration = new TEntry('duration');
        $title = new TEntry('title');
        $description = new TEntry('description');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Entry Date') ], [ $entry_date ] );
        $this->form->addFields( [ new TLabel('Start Hour') ], [ $start_hour ] );
        $this->form->addFields( [ new TLabel('Duration') ], [ $duration ] );
        $this->form->addFields( [ new TLabel('Title') ], [ $title ] );
        $this->form->addFields( [ new TLabel('Description') ], [ $description ] );


        // set sizes
        $id->setSize('100%');
        $entry_date->setSize('100%');
        $start_hour->setSize('100%');
        $duration->setSize('100%');
        $title->setSize('100%');
        $description->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['AgendaEntryForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_entry_date = new TDataGridColumn('entry_date', 'Entry Date', 'left');
        $column_start_hour = new TDataGridColumn('start_hour', 'Start Hour', 'right');
        $column_duration = new TDataGridColumn('duration', 'Duration', 'right');
        $column_title = new TDataGridColumn('title', 'Title', 'left');
        $column_description = new TDataGridColumn('description', 'Description', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_entry_date);
        $this->datagrid->addColumn($column_start_hour);
        $this->datagrid->addColumn($column_duration);
        $this->datagrid->addColumn($column_title);
        $this->datagrid->addColumn($column_description);

        
        $action1 = new TDataGridAction(['AgendaEntryForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
}
