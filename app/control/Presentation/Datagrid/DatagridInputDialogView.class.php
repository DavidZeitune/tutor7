<?php
/**
 * DatagridInputDialogView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridInputDialogView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->setHeight(320);
        
        // add the columns
        $this->datagrid->addColumn( new TDataGridColumn('code',    'Code',    'right',  '10%') );
        $this->datagrid->addColumn( new TDataGridColumn('name',    'Name',    'left',   '30%') );
        $this->datagrid->addColumn( new TDataGridColumn('address', 'Address', 'left',   '30%') );
        $this->datagrid->addColumn( new TDataGridColumn('phone',   'Phone',   'center', '30%') );
        
        // add the actions
        $action1 = new TDataGridAction([$this, 'onInputDialog'],   ['name' => '{name}' ] );
        $this->datagrid->addAction($action1, 'Input', 'fas:external-link-alt');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(_t('Datagrids with input dialog'), $this->datagrid, 'footer'));

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
        $item->address  = 'Memphis, Tennessee';
        $item->phone    = '1111-1111';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '2';
        $item->name     = 'Eric Clapton';
        $item->address  = 'Ripley, Surrey';
        $item->phone    = '2222-2222';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '3';
        $item->name     = 'B.B. King';
        $item->address  = 'Itta Bena, Mississippi';
        $item->phone    = '3333-3333';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code     = '4';
        $item->name     = 'Janis Joplin';
        $item->address  = 'Port Arthur, Texas';
        $item->phone    = '4444-4444';
        $this->datagrid->addItem($item);
    }
    
    /**
     * Open an input dialog
     */
    public static function onInputDialog( $param )
    {
        // input fields
        $name   = new TEntry('name');
        $amount = new TEntry('amount');
        $name->setValue($param['key']);
        
        $form = new BootstrapFormBuilder('input_form');
        $form->addFields( [new TLabel('Name')],     [$name] );
        $form->addFields( [new TLabel('Amount: ')], [$amount] );
        
        // form action
        $form->addAction('Confirm', new TAction(array(__CLASS__, 'onConfirm')), 'fa:save green');
        
        // show input dialot
        new TInputDialog('Input dialog', $form);
    }
    
    /**
     * Show the input dialog data
     */
    public static function onConfirm( $param )
    {
        if (isset($param['amount']) AND $param['amount']) // validate required field
        {
            new TMessage('info', "Name: ". $param['name'] . '; Amount: ' . $param['amount'], new TAction(array('DatagridInputDialogView', 'onReload')));
        }
        else
        {
            new TMessage('error', 'Amount is required');
        }
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
