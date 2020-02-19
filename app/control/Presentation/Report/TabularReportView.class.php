<?php
/**
 * Tabular report View
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TabularReportView extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Customer_report');
        $this->form->setFormTitle( _t('Tabular report over view') );
        
        // create the form fields
        $city_id      = new TDBUniqueSearch('city_id', 'samples', 'City', 'id', 'name');
        $category_id  = new TDBCombo('category_id', 'samples', 'Category', 'id', 'name');
        $output_type  = new TRadioGroup('output_type');
        
        $this->form->addFields( [new TLabel('City')],     [$city_id] );
        $this->form->addFields( [new TLabel('Category')], [$category_id] );
        $this->form->addFields( [new TLabel('Output')],   [$output_type] );
        
        // define field properties
        $city_id->setSize( '80%' );
        $category_id->setSize( '80%' );
        $output_type->setUseButton();
        $city_id->setMinLength(1);
        $options = ['html' =>'HTML', 'pdf' =>'PDF', 'rtf' =>'RTF', 'xls' =>'XLS'];
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        $this->form->addAction( 'Generate', new TAction(array($this, 'onGenerate')), 'fa:download blue');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        parent::add($vbox);
    }

    /**
     * method onGenerate()
     * Executed whenever the user clicks at the generate button
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            // get the form data into
            $data = $this->form->getData();
            
            $repository = new TRepository('ViewSales');
            $criteria   = new TCriteria;
            
            if ($data->city_id)
            {
                $criteria->add(new TFilter('city_id', '=', $data->city_id));
            }
            
            if ($data->category_id)
            {
                $criteria->add(new TFilter('category_id', '=', $data->category_id));
            }
           
            $customers = $repository->load($criteria);
            $format  = $data->output_type;
            
            if ($customers)
            {
                $widths = array(40, 200, 120, 80, 80, 80);
                
                switch ($format)
                {
                    case 'html':
                        $table = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $table = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        $table = new TTableWriterRTF($widths);
                        break;
                    case 'xls':
                        $table = new TTableWriterXLS($widths);
                        break;
                }
                
                if (!empty($table))
                {
                    // create the document styles
                    $table->addStyle('header', 'Helvetica', '16', 'B', '#ffffff', '#4B5D8E');
                    $table->addStyle('title',  'Helvetica', '10', 'B', '#ffffff', '#617FC3');
                    $table->addStyle('datap',  'Helvetica', '10', '',  '#000000', '#E3E3E3', 'LR');
                    $table->addStyle('datai',  'Helvetica', '10', '',  '#000000', '#ffffff', 'LR');
                    $table->addStyle('footer', 'Helvetica', '10', '',  '#2B2B2B', '#B4CAFF');
                    
                    $table->setHeaderCallback( function($table) {
                        $table->addRow();
                        $table->addCell('Sales by customer', 'center', 'header', 6);
                        
                        $table->addRow();
                        $table->addCell('Code',      'center', 'title');
                        $table->addCell('Name',      'left',   'title');
                        $table->addCell('Email',     'left',   'title');
                        $table->addCell('Birthdate', 'center', 'title');
                        $table->addCell('Total',     'center', 'title');
                        $table->addCell('Last purchase', 'center', 'title');
                    });
                    
                    $table->setFooterCallback( function($table) {
                        $table->addRow();
                        $table->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 6);
                    });
                    
                    // controls the background filling
                    $colour= FALSE;
                    
                    // data rows
                    foreach ($customers as $customer)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($customer->id,             'center', $style);
                        $table->addCell($customer->name,           'left',   $style);
                        $table->addCell($customer->email,          'left',   $style);
                        $table->addCell($customer->birthdate,      'center', $style);
                        $table->addCell( number_format($customer->total,2),      'center', $style);
                        $table->addCell($customer->last_date,      'center', $style);
                        
                        $colour = !$colour;
                    }
                    
                    $output = "app/output/tabular.{$format}";
                    
                    // stores the file
                    if (!file_exists($output) OR is_writable($output))
                    {
                        $table->save($output);
                        parent::openFile($output);
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . $output);
                    }
                    
                    // shows the success message
                    new TMessage('info', 'Report generated. Please, enable popups in the browser.');
                }
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
            // fill the form with the active record data
            $this->form->setData($data);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
