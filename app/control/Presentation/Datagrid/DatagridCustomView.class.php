<?php
/**
 * DatagridCustomView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridCustomView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->enablePopover('Details', '<b>Code:</b> {code} <br> <b>Name:</b> {name} <br> <b>City:</b> {city} <br> <b>State:</b> {state}');
        
        // create the datagrid columns
        $code       = new TDataGridColumn('code',    'Code',    'center', '10%');
        $name       = new TDataGridColumn('name',    'Name',    'left',   '30%');
        $city       = new TDataGridColumn('city',    'City',    'left',   '30%');
        $state      = new TDataGridColumn('state',   'State',   'left',   '30%');
        
        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($code,   new TAction([$this, 'onColumnAction'], ['column' => 'code']) );
        $this->datagrid->addColumn($name,   new TAction([$this, 'onColumnAction'], ['column' => 'name']) );
        $this->datagrid->addColumn($city,   new TAction([$this, 'onColumnAction'], ['column' => 'city']) );
        $this->datagrid->addColumn($state,  new TAction([$this, 'onColumnAction'], ['column' => 'state']) );
        
        $code->title  = 'Here is the code';
        $name->title  = 'Here is the name';
        $city->title  = 'Here is the city';
        $state->title = 'Here is the state';
        
        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onView'],   ['code'=>'{code}',  'name' => '{name}'] );
        $action2 = new TDataGridAction([$this, 'onDelete'], ['code'=>'{code}'] );
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1, 'View', 'fa:search blue');
        $this->datagrid->addAction($action2, 'Delete', 'far:trash-alt red');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(_t('Native datagrid'), $this->datagrid));

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
        $item->code   = '1';
        $item->name   = 'Aretha Franklin';
        $item->city   = 'Memphis';
        $item->state  = 'Tennessee (US)';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code   = '2';
        $item->name   = 'Eric Clapton';
        $item->city   = 'Ripley';
        $item->state  = 'Surrey (UK)';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code   = '3';
        $item->name   = 'B.B. King';
        $item->city   = 'Itta Bena';
        $item->state  = 'Mississippi (US)';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code   = '4';
        $item->name   = 'Janis Joplin';
        $item->city   = 'Port Arthur';
        $item->state  = 'Texas (US)';
        $this->datagrid->addItem($item);
    }
    
    /**
     * Executed when the user clicks at the column title
     */
    public function onColumnAction($param)
    {
        // get the parameter and shows the message
        $column = $param['column'];
        new TMessage('info', "You clicked at the column <b>{$column}</b>");
    }
    
    /**
     * Executed when the user clicks at the view button
     */
    public function onView($param)
    {
        // get the parameter and shows the message
        $code = $param['code'];
        $name = $param['name'];
        new TMessage('info', "The code is: <b>$code</b> <br> The name is : <b>$name</b>");
    }
    
    /**
     * Executed when the user clicks at the delete button
     * STATIC Method, does't reload the page when executed
     */
    public static function onDelete($param)
    {
        // get the parameter and shows the message
        $code = $param['code'];
        new TMessage('error', "The register <b>{$code}</b> may not be deleted");
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
