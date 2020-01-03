<?php
/**
 * ProductCardView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ProductCardView extends TPage
{
    private $form, $cards, $pageNavigation;
    
    use Adianti\Base\AdiantiStandardCollectionTrait;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('samples');
        $this->setActiveRecord('Product');
        $this->addFilterField('description');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Product');
        $this->form->setFormTitle(_t('Product cards'));
        
        $description = new TEntry('description');
        $this->form->addFields( [new TLabel('Description:')], [$description] );
        
        $this->form->addAction('Find', new TAction([$this, 'onSearch']), 'fa:search blue');
        $this->form->addActionLink('New',  new TAction(['ProductForm', 'onEdit']), 'fa:plus-circle green');

        // keep the form filled with the search data
        $description->setValue( TSession::getValue( 'Product_description' ) );
        
        // creates the Card View
        $this->cards = new TCardView;
		$this->cards->setContentHeight(170);
		$this->cards->setTitleAttribute('description');
		
		$this->setCollectionObject($this->cards);
		
		$this->cards->setItemTemplate('<div style="float:left;width:50%;padding-right:10px">
		                                   <b>Description</b> <br> {description} <br>
		                                   <b>Stock</b> <br> {stock} <br>
		                                   <b>Price</b> <br> {sale_price}
		                               </div>
		                               <div style="float:right;width:50%">
		                                   <img style="height:100px;float:right;margin:5px" src="{photo_path}">
		                               </div> ');
        
        $edit_action   = new TAction(['ProductForm', 'onEdit'], ['id'=> '{id}']);
        $delete_action = new TAction([$this, 'onDelete'], ['id'=> '{id}', 'register_state' => 'false']);
        
		$this->cards->addAction($edit_action,   'Edit',   'far:edit bg-blue');
		$this->cards->addAction($delete_action, 'Delete', 'far:trash-alt bg-red');
		
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        // creates the page structure using a table
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form); // add a row to the form
        $vbox->add(TPanelGroup::pack('', $this->cards, $this->pageNavigation)); // add a row for page navigation
        
        // add the table inside the page
        parent::add($vbox);
    }
}
