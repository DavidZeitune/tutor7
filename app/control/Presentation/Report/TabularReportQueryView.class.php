<?php
/**
 * Tabular Query Report
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TabularReportQueryView extends TPage
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
        $this->form->setFormTitle( _t('Tabular report') );
        $this->form->setClientValidation(true);
        
        // create the form fields
        $city_id      = new TDBUniqueSearch('city_id', 'samples', 'City', 'id', 'name');
        $output_type  = new TRadioGroup('output_type');
        
        $this->form->addFields( [new TLabel('City')],     [$city_id] );
        $this->form->addFields( [new TLabel('Output')],   [$output_type] );
        
        // define field properties
        $city_id->setSize( '80%' );
        $city_id->setMinLength(0);
        $output_type->setUseButton();
        $options = ['html' =>'HTML', 'pdf' =>'PDF', 'rtf' =>'RTF', 'xls' =>'XLS'];
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        $city_id->addValidation( 'City', new TRequiredValidator);
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
            // get the form data into an active record Customer
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $format = $data->output_type;
            
            // open a transaction with database 'samples'
            $source = TTransaction::open('samples');
            
            // define the query
            $query = "SELECT cs.id as 'id',
                             cs.name as 'name',
                             cs.email as 'email',
                             cs.birthdate as 'birthdate',
                             ct.name as 'category_name'
                       FROM  customer cs, category ct
                      WHERE  cs.category_id = ct.id and cs.city_id = :city_id";
            
            $rows = TDatabase::getData($source, $query, null, [ 'city_id' => $data->city_id ]);
            
            if ($rows)
            {
                $widths = array(40, 200, 80, 120, 80);
                
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
                    $table->addStyle('header', 'Helvetica', '16', 'B', '#ffffff', '#4B8E57');
                    $table->addStyle('title',  'Helvetica', '10', 'B', '#ffffff', '#6CC361');
                    $table->addStyle('datap',  'Helvetica', '10', '',  '#000000', '#E3E3E3', 'LR');
                    $table->addStyle('datai',  'Helvetica', '10', '',  '#000000', '#ffffff', 'LR');
                    $table->addStyle('footer', 'Helvetica', '10', '',  '#2B2B2B', '#B5FFB4');
                    
                    $table->setHeaderCallback( function($table) {
                        $table->addRow();
                        $table->addCell('Customers', 'center', 'header', 5);
                        
                        $table->addRow();
                        $table->addCell('Code',      'center', 'title');
                        $table->addCell('Name',      'left', 'title');
                        $table->addCell('Category',  'center', 'title');
                        $table->addCell('Email',     'left', 'title');
                        $table->addCell('Birthdate', 'center', 'title');
                    });
                    
                    $table->setFooterCallback( function($table) {
                        $table->addRow();
                        $table->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 5);
                    });
                    
                    // controls the background filling
                    $colour= FALSE;
                    
                    // data rows
                    foreach ($rows as $row)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($row['id'],             'center', $style);
                        $table->addCell($row['name'],           'left',   $style);
                        $table->addCell($row['category_name'],  'center', $style);
                        $table->addCell($row['email'],          'left',   $style);
                        $table->addCell($row['birthdate'],      'center', $style);
                        
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
                    new TMessage('info', "Report generated. Please, enable popups in the browser. <br> <a href='$output'>Click here for download</a>");
                }
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
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
