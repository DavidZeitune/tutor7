<?php
/**
 * DatagridLinkView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridLinkView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->disableDefaultClick();
        $this->datagrid->width = '100%';
        
        // create the datagrid columns
        $code       = new TDataGridColumn('code',    'Code',    'center', '10%');
        $name       = new TDataGridColumn('name',    'Name',    'left',   '30%');
        $email      = new TDataGridColumn('email',   'Email',   'left',   '30%');
        $phone      = new TDataGridColumn('phone',   'Phone',   'left',   '30%');
        
        $email->setTransformer( function ($value) {
            if ($value)
            {
                $icon  = "<i class='far fa-envelope' aria-hidden='true'></i>";
                return "{$icon} <a generator='adianti' href='index.php?class=SingleEmailForm&method=onLoad&scroll=0&email=$value'>$value</a>";
            }
            return $value;
        });
        
        $phone->setTransformer( function ($value) {
            if ($value)
            {
                $value = str_replace([' ','-','(',')'],['','','',''], $value);
                $icon  = "<i class='fab fa-whatsapp' aria-hidden='true'></i>";
                return "{$icon} <a target='newwindow' href='https://api.whatsapp.com/send?phone=55{$value}&text=Olá'> {$value} </a>";
            }
            return $value;
        });
        
        // add the columns to the datagrid
        $this->datagrid->addColumn($code);
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($email);
        $this->datagrid->addColumn($phone);
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack('', $this->datagrid));

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
        $item->code     = '1';
        $item->name     = 'Aretha Franklin';
        $item->email    = 'aretha@email.com';
        $item->phone    = '51 1111-1111';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '2';
        $item->name     = 'Eric Clapton';
        $item->email    = 'eric@email.com';
        $item->phone     = '51 2222-2222';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '3';
        $item->name     = 'B.B. King';
        $item->email    = 'king@email.com';
        $item->phone    = '51 3333-3333';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '4';
        $item->name     = 'Janis Joplin';
        $item->email    = 'janis@email.com';
        $item->phone    = '51 4444-4444';
        $this->datagrid->addItem($item);
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
