<?php
/**
 * CheckoutFormView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CheckoutFormView extends TPage
{
    private $form;      // search form
    private $datagrid;  // listing
    private $total;
    private $cartgrid;
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the search form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        new TSession;
        
        // creates the form
        $this->form = new TForm('form_search_product');
        
        // create the form fields
        $description   = new TEntry('description');
        $description->setSize(170);
        $description->setValue(TSession::getValue('product_description'));
        
        $table = new TTable;
        
        $row = $table->addRow();
        $cell=$row->addCell('');
        $cell->width= 50;
        $row->addCell($description);
        
        // creates the action button
        $button1=new TButton('find');
        $button1->setAction(new TAction(array($this, 'onSearch')), 'Find');
        $button1->setImage('fa:search');
        
        $row->addCell($button1);
        $this->form->add($table);
        $this->form->setFields(array($description, $button1));
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->cartgrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width:100%';
        $this->cartgrid->style = 'width:100%';

        // creates the datagrid columns
        $this->datagrid->addColumn( new TDataGridColumn('id',  'ID',  'center', '30') );
        $this->datagrid->addColumn( new TDataGridColumn('description',  'Description',  'left') );
        $this->datagrid->addColumn( new TDataGridColumn('sale_price',  'Price',  'center') );
        
        $this->cartgrid->addColumn( new TDataGridColumn('id',  'ID',  'center', '30') );
        $this->cartgrid->addColumn( new TDataGridColumn('description',  'Description',  'left') );
        $this->cartgrid->addColumn( new TDataGridColumn('sale_price',  'Price',  'center') );
        
        // creates datagrid actions
        
        $action1 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}' ] );
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id' => '{id}' ] );
        
        $this->datagrid->addAction($action1, 'Select', 'far:check-circle green');
        $this->cartgrid->addAction($action2, 'Delete', 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        $this->cartgrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        
        // creates the page structure using a table
        $table1 = new TTable;
        $table1->style = 'width: 100%';
        $table1->addRow()->addCell($this->form)->height='50';
        $table1->addRow()->addCell($this->datagrid);
        $table1->addRow()->addCell($this->pageNavigation);
        
        $this->total = new TLabel('');
        $this->total->setFontStyle('b');
        
        $table2 = new TTable;
        $table2->style = 'width: 100%';
        $table2->addRow()->addCell($this->total)->height = '50';
        $table2->addRow()->addCell($this->cartgrid);
        
        $hbox = new THBox;
        $hbox->add($table1)->style.='vertical-align:top; width: 45%';
        $hbox->add($table2)->style.='vertical-align:top; width: 45%';
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($hbox);
        //$vbox->add($this->pageNavigation);

        parent::add($vbox);
    }
    
    
    /**
     * Put a product inside the cart
     */
    public function onSelect($param)
    {
        // get the cart objects from session 
        $cart_objects = TSession::getValue('cart_objects');
        
        TTransaction::open('samples');
        $product = new Product($param['key']); // load the product
        $cart_objects[$product->id] = $product; // add the product inside the array
        TSession::setValue('cart_objects', $cart_objects); // put the array back to the session
        TTransaction::close();
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * Remove a product from the cart
     */
    public function onDelete($param)
    {
        // get the cart objects from session
        $cart_objects = TSession::getValue('cart_objects');
        unset($cart_objects[$param['key']]); // remove the product from the array
        TSession::setValue('cart_objects', $cart_objects); // put the array back to the session
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // check if the user has filled the form
        if ($data->description)
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('description', 'like', "%{$data->description}%");
            
            // stores the filter in the session
            TSession::setValue('product_filter1', $filter);
            TSession::setValue('product_description',   $data->description);
            
        }
        else
        {
            TSession::setValue('product_filter1', NULL);
            TSession::setValue('product_description',   '');
        }
        
        // fill the form with data again
        $this->form->setData($data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            // creates a repository for Product
            $repository = new TRepository('Product');
            $limit = 10;
            
            // creates a criteria
            $criteria = new TCriteria;
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            $criteria->setProperty('order', 'description');
            
            if (TSession::getValue('product_filter1'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('product_filter1'));
            }
            
            // load the objects according to criteria
            $products = $repository->load($criteria);
            $this->datagrid->clear();
            if ($products)
            {
                foreach ($products as $product)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($product);
                }
            }
            
            $this->cartgrid->clear();
            $cart_objects = TSession::getValue('cart_objects');
            $total = 0;
            if ($cart_objects)
            {
                foreach ($cart_objects as $object)
                {
                    $this->cartgrid->addItem($object);
                    $total += $object->sale_price;
                }
            }
            $this->total->setValue(number_format($total, 2, ',', '.'));
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded)
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
?>
