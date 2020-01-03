<?php
/**
 * HTML5 manual form
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TemplateHtmlManualForm extends TPage
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/raw_form.html');
        
        try
        {
            // define replacements for the main section
            $replace = array();
            $replace['first_name']  = 'John';
            $replace['last_name']   = 'Scott';
            $replace['user_name']   = 'john';
            
            // replace the main section variables
            $this->html->enableSection('main', $replace);
            
            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $vbox->add($this->html);
    
            parent::add($vbox);            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Send data
     */
    public static function onSend($param)
    {
        echo '<pre>';
        print_r($param);
        echo '</pre>';
    } 
}
