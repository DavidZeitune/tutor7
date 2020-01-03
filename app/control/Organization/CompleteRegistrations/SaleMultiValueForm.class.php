<?php
/**
 * SaleForm Registration
 * @author  <your name here>
 */
class SaleMultiValueForm extends TPage
{
    protected $form; // form
    protected $dt_venda;
    protected $product_list;
    protected $detail_row;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct($param);
        
        // creates the form
        $this->form   = new BootstrapFormBuilder('form_SaleMultiValue');
        $this->form->setFormTitle('Sale form');
        
        // master fields
        $id             = new TEntry('id');
        $date           = new TDate('date');
        $customer_id    = new TDBSeekButton('customer_id', 'samples', $this->form->getName(), 'Customer', 'name', 'customer_id', 'customer_name');
        $customer_name  = new TEntry('customer_name');
        $obs            = new TText('obs');
        
        $id->setSize(40);
        $id->setEditable(false);
        $date->setSize(140);
        $obs->setSize('100%',50);
        $customer_id->setSize(50);
        $customer_name->setEditable(false);
        $customer_name->setSize('calc(100% - 200px)');
        
        $date->addValidation('Date', new TRequiredValidator);
        $customer_id->addValidation('Customer', new TRequiredValidator);
        
        $label_date     = new TLabel('Date (*)');
        $label_customer = new TLabel('Customer (*)');
        
        $this->form->addFields( [new TLabel('ID')], [$id] );
        $this->form->addFields( [$label_date], [$date] );
        $this->form->addFields( [$label_customer], [$customer_id, $customer_name] );
        $this->form->addFields( [new TLabel('Obs')], [$obs] );
        
        $label_date->setFontColor('#FF0000');
        
        // create detail fields
        $product_id = new TDBUniqueSearch('product_id[]', 'samples', 'Product', 'id', 'description');
        $product_id->setMinLength(1);
        $product_id->setSize('100%');
        $product_id->setMask('{description} ({id})');
        $product_id->setChangeAction(new TAction(array($this, 'onChangeProduct')));
        
        $product_price = new TEntry('product_price[]');
        $product_price->setNumericMask(2,',','.', true);
        $product_price->setSize('100%');
        $product_price->style = 'text-align: right';
        $product_price->setEditable(FALSE);
        
        $product_amount = new TEntry('product_amount[]');
        $product_amount->setNumericMask(2,',','.', true);
        $product_amount->setSize('100%');
        $product_amount->setExitAction(new TAction(array($this, 'onUpdateTotal')));
        $product_amount->style = 'text-align: right';
        
        $product_total = new TEntry('product_total[]');
        $product_total->setEditable(FALSE);
        $product_total->setNumericMask(2,',','.', true);
        $product_total->setSize('100%');
        $product_total->style = 'text-align: right';
        
        $this->form->addField($product_id);
        $this->form->addField($product_price);
        $this->form->addField($product_amount);
        $this->form->addField($product_total);
        
        // detail
        $this->product_list = new TFieldList;
        $this->product_list->addField( '<b>Product</b>', $product_id,     ['width' => '40%']);
        $this->product_list->addField( '<b>Price</b>',   $product_price,  ['width' => '20%']);
        $this->product_list->addField( '<b>Amount</b>',  $product_amount, ['width' => '20%']);
        $this->product_list->addField( '<b>Total</b>',   $product_total,  ['width' => '20%', 'sum' => true]);
        $this->product_list-> width = '100%';
        $this->product_list->enableSorting();
        
        $this->form->addFields( [new TFormSeparator('Products') ] );
        $this->form->addFields( [$this->product_list] );
        
        $this->form->addAction( _t('Save'),  new TAction( [$this, 'onSave'] ),  'fa:save green' );
        $this->form->addAction( _t('Clear'), new TAction( [$this, 'onClear'] ), 'fa:eraser red' );
        
        // update total when remove item row
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        parent::add($container);
    }
    
    /**
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            TTransaction::open('samples');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $sale = new Sale($key);
                $this->form->setData($sale);
                
                $sale_items = SaleItem::where('sale_id', '=', $sale->id)->load();
                
                $this->product_list->addHeader();
                if ($sale_items)
                {
                    foreach($sale_items  as $item )
                    {
                        $item->product_price  = $item->sale_price;
                        $item->product_amount = $item->amount;
                        $item->product_total  = $item->sale_price * $item->amount;
                        $this->product_list->addDetail($item);
                    }
                    $this->product_list->addCloneAction();
                }
                else
                {
                    $this->onClear($param);
                }
                
                TTransaction::close(); // close transaction
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Change product
     */
    public static function onChangeProduct($param)
    {
        $input_id = $param['_field_id'];
        $product_id = $param['_field_value'];
        $input_pieces = explode('_', $input_id);
        $unique_id = end($input_pieces);
        
        if ($product_id)
        {
            $response = new stdClass;
            
            try
            {
                TTransaction::open('samples');
                $product = Product::find($product_id);
                $response->{'product_price_'.$unique_id} = number_format($product->sale_price,2,',', '.');
                $response->{'product_amount_'.$unique_id} = '1,00';
                $response->{'product_total_'.$unique_id} = number_format($product->sale_price,2,',', '.');
                
                TForm::sendData('form_SaleMultiValue', $response);
                TTransaction::close();
            }
            catch (Exception $e)
            {
                TTransaction::rollback();
            }
        }
    }
    
    /**
     * Update the total based on the sale price, amount and discount
     */
    public static function onUpdateTotal($param)
    {
        $input_id = $param['_field_id'];
        $product_id = $param['_field_value'];
        $input_pieces = explode('_', $input_id);
        $unique_id = end($input_pieces);
        parse_str($param['_field_data'], $field_data);
        $row = $field_data['row'];
        
        $sale_price = (double) str_replace(['.', ','], ['', '.'], $param['product_price'][$row]);
        $amount     = (double) str_replace(['.', ','], ['', '.'], $param['product_amount'][$row]);
        
        $obj = new StdClass;
        $obj->{'product_total_'.$unique_id} = number_format( ($sale_price * $amount), 2, ',', '.');
        TForm::sendData('form_SaleMultiValue', $obj);
    }
    
    /**
     * Clear form
     */
    public function onClear($param)
    {
        $this->product_list->addHeader();
        $this->product_list->addDetail( new stdClass );
        $this->product_list->addCloneAction();
    }
    
    /**
     * Save the sale and the sale items
     */
    public static function onSave($param)
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            $id = (int) $param['id'];
            $sale = new Sale($id);
            $sale->date = $param['date'];
            $sale->customer_id = $param['customer_id'];
            $sale->obs = $param['obs'];
            $total = 0;
            $sale->store();
            
            $sale_items = SaleItem::where('sale_id', '=', $sale->id)->delete();
            
            if( !empty($param['product_id']) AND is_array($param['product_id']) )
            {
                foreach( $param['product_id'] as $row => $product_id)
                {
                    if ($product_id)
                    {
                        $item = new SaleItem;
                        $item->product_id  = $product_id;
                        $item->sale_price  = (float) str_replace(['.',','], ['','.'], $param['product_price'][$row]);
                        $item->amount      = (float) str_replace(['.',','], ['','.'], $param['product_amount'][$row]);
                        $item->discount    = 0;
                        $item->total       = $item->sale_price * $item->amount;
                        
                        $total += $item->total;
                        $item->sale_id = $sale->id;
                        $item->store();
                    }
                }
            }
            
            $sale->total = $total;
            $sale->store(); // stores the object
            
            $data = new stdClass;
            $data->id = $sale->id;
            TForm::sendData('form_SaleMultiValue', $data);
            TTransaction::close(); // close the transaction
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
