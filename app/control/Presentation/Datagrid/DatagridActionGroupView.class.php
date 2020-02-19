<?php
/**
 * DatagridActionGroupView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridActionGroupView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->enablePopover('Details', '<b>Code:</b> {code} <br> <b>Name:</b> {name} <br> <b>City:</b> {city} <br> <b>State:</b> {state}');
        
        // create the datagrid columns
        $code       = new TDataGridColumn('code',    'Code',    'center', '10%');
        $name       = new TDataGridColumn('name',    'Name',    'left',   '30%');
        $city       = new TDataGridColumn('city',    'City',    'left',   '30%');
        $state      = new TDataGridColumn('state',   'State',   'left',   '30%');
        
        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($code);
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($city);
        $this->datagrid->addColumn($state);
        
        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onView'],     ['code' => '{code}',  'name' => '{name}'] );
        $action2 = new TDataGridAction([$this, 'onDelete'],   ['code' => '{code}' ] );
        $action3 = new TDataGridAction([$this, 'onViewCity'], ['city' => '{city}' ] );
        
        $action1->setLabel('View name');
        $action1->setImage('fa:search #7C93CF');
        
        $action2->setLabel('Try to delete');
        $action2->setImage('far:trash-alt red');
        
        $action3->setLabel('View city');
        $action3->setImage('far:hand-pointer green');
        
        $action_group = new TDataGridActionGroup('Actions ', 'fa:th');
        
        $action_group->addHeader('Available Options');
        $action_group->addAction($action1);
        $action_group->addAction($action2);
        $action_group->addSeparator();
        $action_group->addHeader('Another Options');
        $action_group->addAction($action3);
        
        // add the actions to the datagrid
        $this->datagrid->addActionGroup($action_group);
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(_t('Datagrids Actions group'), $this->datagrid, 'footer'));

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
     * Executed when the user clicks at the view city button
     */
    public function onViewCity($param)
    {
        // get the parameter and shows the message
        $city = $param['city'];
        new TMessage('info', "The city is: <strong>$city</strong>");
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
