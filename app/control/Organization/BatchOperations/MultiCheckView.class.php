<?php
/**
 * MultiCheckView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiCheckView extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formDatagrid;
    protected $postAction;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('samples');                // defines the database
        parent::setActiveRecord('Product');            // defines the active record
        parent::setDefaultOrder('id', 'asc');          // defines the default order
        parent::addFilterField('description', 'like'); // add a filter field
        parent::setTransformer( array($this, 'onBeforeLoad') );
        
        // creates the form, with a table inside
        $this->form = new BootstrapFormBuilder('form_search_Product');
        $this->form->setFormTitle(_t('Multi check form'));
        
        // create the form fields
        $description = new TEntry('description');
        
        // add a row for the filter field
        $this->form->addFields( [new TLabel('Description')], [$description] );
        
        $this->form->setData( TSession::getValue('Product_filter_data') );
        $this->form->addAction( _t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        
        // create the datagrid form wrapper
        $this->formDatagrid = new TForm('datagrid_form');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        $this->formDatagrid->add($this->datagrid);
        
        // creates the datagrid columns
        $check       = $this->datagrid->addColumn( new TDataGridColumn('check',  'Check',  'center', '40') );
        $id          = $this->datagrid->addColumn( new TDataGridColumn('id',  'ID',  'center', '40') );
        $description = $this->datagrid->addColumn( new TDataGridColumn('description',  'Description',  'left') );
        $stock       = $this->datagrid->addColumn( new TDataGridColumn('stock',  'Stock',  'center') );
        $sale_price  = $this->datagrid->addColumn( new TDataGridColumn('sale_price',  'Sale Price',  'center') );
        $unity       = $this->datagrid->addColumn( new TDataGridColumn('unity',  'Unity',  'center') );
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));

        $this->postAction = new TAction(array($this, 'onPost'));
        $post = new TButton('post');
        $post->setAction($this->postAction);
        $post->setImage('far:check-circle green');
        $post->setLabel('Send');
        
        $this->formDatagrid->addField($post);
        
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel = TPanelGroup::pack('', $this->formDatagrid, $this->pageNavigation));
        $panel->getBody()->style = 'overflow-x: auto';
        $container->add($post);
        
        parent::add($container);
    }
    
    public function onReload( $param = NULL )
    {
        // update the post action parameters to pass
        // offset, limit, page and other info in
        // order to preserve the pagination after post
        $this->postAction->setParameters($param); // important!
        
        return parent::onReload( $param );
    }
    
    /**
     * Transform the objects before load them into the datagrid
     */
    public function onBeforeLoad( $objects )
    {
        foreach ($objects as $object)
        {
            $object->check = new TCheckButton('check_'.$object->id);
            $object->check->setIndexValue('on');
            $this->form->addField($object->check); // important!
        }
    }
    
    /**
     * Get post data and redirects to the next screen
     */
    public function onPost( $param )
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        $selected_products = array();
        
        foreach ($this->form->getFields() as $name => $field)
        {
            if ($field instanceof TCheckButton)
            {
                $parts = explode('_', $name);
                $id = $parts[1];
                
                if ($field->getValue() == 'on')
                {
                    $selected_products[] = $id;
                }
            }
        }
        TSession::setValue('selected_products', $selected_products );
        TApplication::loadPage('MultiCheck2View');
    }
}
