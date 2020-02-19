<?php
/**
 * DatagridExportView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DatagridExportView extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width:100%';
        
        // create the datagrid columns
        $code       = new TDataGridColumn('code',    'Code',    'center', '10%');
        $name       = new TDataGridColumn('name',    'Name',    'left',   '30%');
        $city       = new TDataGridColumn('city',    'City',    'left',   '30%');
        $state      = new TDataGridColumn('state',   'State',   'left',   '30%');
        
        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($code);
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($city);
        $this->datagrid->addColumn($state);
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $panel = new TPanelGroup( _t('Datagrid export'));
        $panel->add( $this->datagrid );
        $panel->addFooter('footer');
        
        $panel->addHeaderActionLink( 'Save as PDF', new TAction([$this, 'exportAsPDF'], ['register_state' => 'false']), 'far:file-pdf red' );
        $panel->addHeaderActionLink( 'Save as CSV', new TAction([$this, 'exportAsCSV'], ['register_state' => 'false']), 'fa:table blue' );
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

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
        $item->code   = '1';
        $item->name   = 'Aretha Franklin';
        $item->city   = 'Memphis';
        $item->state  = 'Tennessee (US)';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code   = '2';
        $item->name   = 'Eric Clapton';
        $item->city   = 'Ripley';
        $item->state  = 'Surrey (UK)';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code   = '3';
        $item->name   = 'B.B. King';
        $item->city   = 'Itta Bena';
        $item->state  = 'Mississippi (US)';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code   = '4';
        $item->name   = 'Janis Joplin';
        $item->city   = 'Port Arthur';
        $item->state  = 'Texas (US)';
        $this->datagrid->addItem($item);
    }
    
    /**
     * Export datagrid as PDF
     */
    public function exportAsPDF($param)
    {
        try
        {
            // string with HTML contents
            $html = clone $this->datagrid;
            $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($contents);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $file = 'app/output/datagrid-export.pdf';
            
            // write and open file
            file_put_contents($file, $dompdf->output());
            
            $window = TWindow::create('Export', 0.8, 0.8);
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
    
    /**
     * Export datagrid as CSV
     */
    public function exportAsCSV($param)
    {
        try
        {
            // get datagrid raw data
            $data = $this->datagrid->getOutputData();
            
            if ($data)
            {
                $file    = 'app/output/datagrid-export.csv';
                $handler = fopen($file, 'w');
                foreach ($data as $row)
                {
                    fputcsv($handler, $row);
                }
                
                fclose($handler);
                parent::openFile($file);
            }
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
}
