<?php
/**
 * Template View pattern implementation
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TemplateInvoiceView extends TPage
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/invoice.html');

        try
        {
            // define replacements for the main section
            $invoice = new stdClass;
            $invoice->id   = '001002003';
            $invoice->date = '2019-03-20';
            $invoice->order_date = '2019-03-20';
            $invoice->pay_method = 'Paypal';
            $invoice->pay_account = 'john@email.com';
            $invoice->shipping = 25;
            
            $customer = new stdClass;
            $customer->name       = 'John gray';
            $customer->address    = 'Nice Street, 123';
            $customer->complement = 'Apt. 456';
            $customer->city       = 'Springfield, ST 54321';
            
            $shipping = new stdClass;
            $shipping->name       = 'Jane white';
            $shipping->address    = 'Nice Street, 234';
            $shipping->complement = 'Apt. 123';
            $shipping->city       = 'Springfield, ST 54321';
            
            $replace = array();
            $replace['invoice']       = $invoice;
            $replace['customer']      = $customer;
            $replace['shipping']      = $shipping;
            
            $replace['items'] = [ [ 'code'        => '001',
                                    'description' => 'Chocolate',
                                    'price'       => 100,
                                    'quantity'    => 1 ],
                                  [ 'code'        => '002',
                                    'description' => 'Cofee',
                                    'price'       => 100,
                                    'quantity'    => 2 ],
                                  [ 'code'        => '003',
                                    'description' => 'Water',
                                    'price'       => 100,
                                    'quantity'    => 3 ] ];
                                                         
            // replace the main section variables
            $this->html->enableSection('main', $replace);
            
            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            
            $panel = new TPanelGroup('Invoice');
            $panel->addHeaderActionLink('Export', new TAction([$this, 'onExportPDF'], ['static' => '1']), 'fa:save' );
            $panel->add($this->html);
            
            $vbox->add($panel);
            parent::add($vbox);            
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function onExportPDF($param)
    {
        try
        {
            // string with HTML contents
            $html = clone $this->html;
            $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($contents);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $file = 'app/output/invoice.pdf';
            
            // write and open file
            file_put_contents($file, $dompdf->output());
            
            $window = TWindow::create('Invoice', 0.8, 0.8);
            $object = new TElement('object');
            $object->data  = $file;
            $object->type  = 'application/pdf';
            $object->style = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
