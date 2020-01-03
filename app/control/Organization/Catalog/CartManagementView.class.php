<?php
class CartManagementView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer("adianti_right_panel");
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        
        // add the columns
        $this->datagrid->addColumn( new TDataGridColumn('id',  'ID',  'center', '10%') );
        $this->datagrid->addColumn( new TDataGridColumn('description',  'Description',  'left',   '30%') );
        $this->datagrid->addColumn( new TDataGridColumn('amount',  'Amount',  'right',   '30%') );
        $this->datagrid->addColumn( new TDataGridColumn('sale_price', 'Price', 'right',   '30%') );
        
        $action1 = new TDataGridAction([$this, 'onDelete'],   ['id'=>'{id}' ] );
        $this->datagrid->addAction($action1, 'Delete', 'far:trash-alt red');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $back = new TActionLink('Close', new TAction(array($this, 'onClose')), 'black', null, null, 'fa:times red');
        $back->addStyleClass('btn btn-default btn-sm');
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($back);
        
        parent::add($panel);
    }
    
    /**
     * Delete an item from cart items
     */
    public function onDelete( $param )
    {
        $cart_items = TSession::getValue('cart_items');
        unset($cart_items[ $param['key'] ]);
        TSession::setValue('cart_items', $cart_items);
        
        $this->onReload();
    }
    
    /**
     * Reload the cart list
     */
    public function onReload()
    {
        $cart_items = TSession::getValue('cart_items');
        
        try
        {
            TTransaction::open('samples');
            $this->datagrid->clear();
            foreach ($cart_items as $id => $amount)
            {
                $product = new Product($id);
                
                $item = new StdClass;
                $item->id          = $product->id;
                $item->description = $product->description;
                $item->amount      = $amount;
                $item->sale_price  = $product->sale_price;
                
                $this->datagrid->addItem( $item );
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
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
    
    /**
     * Close side panel
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
