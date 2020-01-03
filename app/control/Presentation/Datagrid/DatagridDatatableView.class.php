<?php
/**
 * DatagridDatatableView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridDatatableView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        $this->datagrid->datatable = 'true'; // turn on Datatables
        
        // add the columns
        $this->datagrid->addColumn( new TDataGridColumn('code',        'Code',      'center') );
        $this->datagrid->addColumn( new TDataGridColumn('name',        'Name',      'left') );
        $this->datagrid->addColumn( new TDataGridColumn('address',     'Address',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('phone',       'Phone',     'left') );
        $this->datagrid->addColumn( new TDataGridColumn('birthdate',   'Birthdate', 'left') );
        $this->datagrid->addColumn( new TDataGridColumn('city',        'City',      'left') );
        $this->datagrid->addColumn( new TDataGridColumn('status',      'Status',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('email',       'Email',     'left') );
        $this->datagrid->addColumn( new TDataGridColumn('gender',      'Gender',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other1',       'Other1',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other2',       'Other2',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other3',       'Other3',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other4',       'Other4',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other5',       'Other5',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other6',       'Other6',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other7',       'Other7',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other8',       'Other8',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other9',       'Other9',    'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other10',       'Other10',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other11',       'Other11',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other12',       'Other12',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other13',       'Other13',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other14',       'Other14',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other15',       'Other15',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other16',       'Other16',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other17',       'Other17',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other18',       'Other18',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other19',       'Other19',   'left') );
        $this->datagrid->addColumn( new TDataGridColumn('other20',       'Other20',   'left') );
        
        $action1 = new TDataGridAction([$this, 'onView'],   ['code'=>'{code}',  'name' => '{name}'] );
        $this->datagrid->addAction($action1, 'View', 'fa:search blue');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $panel = new TPanelGroup( 'Datagrid datatable' );
        $panel->add($this->datagrid);
        $panel->addFooter('footer');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '1';
        $item->name      = 'My friend nr. 1';
        $item->address   = 'Street';
        $item->phone     = '1111-1111';
        $item->birthdate = '12/12/1990';
        $item->city      = 'New York';
        $item->status    = 'Married';
        $item->email     = 'friend1@test.com';
        $item->gender    = 'male';
        $item->other1    = 'other1';
        $item->other2    = 'other2';
        $item->other3    = 'other3';
        $item->other4    = 'other4';
        $item->other5    = 'other5';
        $item->other6    = 'other6';
        $item->other7    = 'other7';
        $item->other8    = 'other8';
        $item->other9    = 'other9';
        $item->other10   = 'other10';
        $item->other11   = 'other11';
        $item->other12   = 'other12';
        $item->other13   = 'other13';
        $item->other14   = 'other14';
        $item->other15   = 'other15';
        $item->other16   = 'other16';
        $item->other17   = 'other17';
        $item->other18   = 'other18';
        $item->other19   = 'other19';
        $item->other20   = 'other20';
        
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '2';
        $item->name      = 'My friend nr. 2';
        $item->address   = 'Street';
        $item->phone     = '2222-2222';
        $item->birthdate = '12/12/1990';
        $item->city      = 'New York';
        $item->status    = 'Married';
        $item->email     = 'friend2@test.com';
        $item->gender    = 'female';
        $item->other1    = 'other1';
        $item->other2    = 'other2';
        $item->other3    = 'other3';
        $item->other4    = 'other4';
        $item->other5    = 'other5';
        $item->other6    = 'other6';
        $item->other7    = 'other7';
        $item->other8    = 'other8';
        $item->other9    = 'other9';
        $item->other10   = 'other10';
        $item->other10   = 'other10';
        $item->other11   = 'other11';
        $item->other12   = 'other12';
        $item->other13   = 'other13';
        $item->other14   = 'other14';
        $item->other15   = 'other15';
        $item->other16   = 'other16';
        $item->other17   = 'other17';
        $item->other18   = 'other18';
        $item->other19   = 'other19';
        $item->other20   = 'other20';
        
        $this->datagrid->addItem($item);
    }
    
    /**
     * Executed when the user clicks at the view button
     */
    public static function onView($param)
    {
        // get the parameter and shows the message
        $name = $param['name'];
        new TMessage('info', "The name is: <b>{$name}</b>");
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
