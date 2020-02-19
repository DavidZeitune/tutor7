<?php
/**
 * DatagridHScrollView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridHScrollView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'min-width: 1900px';
        
        // add the columns
        $this->datagrid->addColumn( new TDataGridColumn('code',      'Code',      'center') );
        $this->datagrid->addColumn( new TDataGridColumn('name',      'Name',      'left') );
        $this->datagrid->addColumn( new TDataGridColumn('birthdate', 'Birthdate', 'left') );
        $this->datagrid->addColumn( new TDataGridColumn('phone',     'Phone',     'left') );
        $this->datagrid->addColumn( new TDataGridColumn('email',     'Email',     'left') );
        $this->datagrid->addColumn( new TDataGridColumn('city',      'City',      'left') );
        $this->datagrid->addColumn( new TDataGridColumn('state',     'State',     'left') );
        $this->datagrid->addColumn( new TDataGridColumn('country',   'Country',   'left') );
        
        $action1 = new TDataGridAction([$this, 'onView'],   ['code'=>'{code}',  'name' => '{name}'] );
        $this->datagrid->addAction($action1, 'View', 'fa:search blue');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $panel = new TPanelGroup(_t('Horizontal Scrollable Datagrids'));
        $panel->add($this->datagrid);
        $panel->addFooter('footer');
        
        // turn on horizontal scrolling inside panel body
        $panel->getBody()->style = "overflow-x:auto;";
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
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
        $item->code       = '1';
        $item->name       = 'Aretha Franklin';
        $item->birthdate  = '25/03/1942';
        $item->phone      = '08 18 1235 1412 1231';
        $item->email      = 'aretha@email.com';
        $item->city       = 'Memphis';
        $item->state      = 'Tennessee';
        $item->country    = 'US';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code       = '2';
        $item->name       = 'Eric Clapton';
        $item->birthdate  = '30/03/1945';
        $item->phone      = '08 18 1235 1412 7476';
        $item->email      = 'eric@email.com';
        $item->city       = 'Ripley';
        $item->state      = 'Surrey';
        $item->country    = 'UK';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code       = '3';
        $item->name       = 'B.B. King';
        $item->birthdate  = '16/09/1925';
        $item->phone      = '08 18 1235 1412 6574';
        $item->email      = 'king@email.com';
        $item->city       = 'Itta Bena';
        $item->state      = 'Mississippi';
        $item->country    = 'US';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code       = '4';
        $item->name       = 'Janis Joplin';
        $item->birthdate  = '19/01/1943';
        $item->phone      = '08 18 1235 1412 6584';
        $item->email      = 'janis@email.com';
        $item->city       = 'Port Arthur';
        $item->state      = 'Texas';
        $item->country    = 'US';
        $this->datagrid->addItem($item);
    }
    
    /**
     * Executed when the user clicks at the view button
     */
    public static function onView($param)
    {
        // get the parameter and shows the message
        $code = $param['code'];
        $name = $param['name'];
        new TMessage('info', "The code is: <b>$code</b> <br> The name is : <b>$name</b>");
    }
    
    /**
     * shows the page
     */
    public function show()
    {
        $this->onReload();
        parent::show();
    }
}
