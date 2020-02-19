<?php
/**
 * FormFieldListView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormFieldListView extends TPage
{
    private $form;
    private $fieldlist;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // create form and table container
        $this->form = new BootstrapFormBuilder('my_form');
        $this->form->setFormTitle(_t('Form field list'));
        
        $combo = new TCombo('combo[]');
        $combo->enableSearch();
        $combo->addItems(['1'=>'One','2'=>'Two','3'=>'Three','4'=>'Four','5'=>'Five']);
        $combo->setSize('100%');
        
        $text = new TEntry('text[]');
        $text->setSize('100%');
        
        $number = new TEntry('number[]');
        $number->setNumericMask(2,',','.', true);
        $number->setSize('100%');
        $number->style = 'text-align: right';
        
        $date = new TDate('date[]');
        $date->setSize('100%');
        
        $this->fieldlist = new TFieldList;
        $this->fieldlist->generateAria();
        $this->fieldlist->width = '100%';
        $this->fieldlist->name  = 'my_field_list';
        $this->fieldlist->addField( '<b>Combo</b>',  $combo,  ['width' => '25%'] );
        $this->fieldlist->addField( '<b>Text</b>',   $text,   ['width' => '25%'] );
        $this->fieldlist->addField( '<b>Number</b>', $number, ['width' => '25%', 'sum' => true] );
        $this->fieldlist->addField( '<b>Date</b>',   $date,   ['width' => '25%'] );
        
        $this->fieldlist->enableSorting();
        
        $this->form->addField($combo);
        $this->form->addField($text);
        $this->form->addField($number);
        $this->form->addField($date);
        
        $this->fieldlist->addButtonFunction("__adianti_post_data('my_form','class=FormFieldListView&method=onSave&key=' + tfieldlist_get_row(this))", 'fa:info-circle blue', 'Show "Text" field');
        
        $this->fieldlist->addHeader();
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addCloneAction();
        
        // add field list to the form
        $this->form->addContent( [$this->fieldlist] );
        
        // form actions
        $this->form->addAction( 'Save', new TAction([$this, 'onSave']), 'fa:save blue');
        $this->form->addAction( 'Clear', new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addAction( 'Fill', new TAction([$this, 'onFill']), 'fas:pencil-alt green');
        $this->form->addAction( 'Clear/Fill', new TAction([$this, 'onClearFill']), 'fas:pencil-alt orange');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        parent::add($vbox);
    }
    
    /**
     * Clear form
     */
    public static function onClear($param)
    {
        TFieldList::clearRows('my_field_list');
        //TFieldList::addRows('my_field_list', 4);
    }
    
    /**
     * Fill data
     */
    public static function onFill($param)
    {
        $data = new stdClass;
        $data->combo  = [ 1,2,3,4,5 ];
        $data->text   = [ 'Part. One', 'Part. Two', 'Part. Three', 'Part. Four', 'Part. Five' ];
        $data->number = [ '10,10','20,20', '30,30', '40,40', '50,50' ];
        $data->date   = [ date('Y-m-d'),
                          date('Y-m-d', strtotime("+1 days")),
                          date('Y-m-d', strtotime("+2 days")),
                          date('Y-m-d', strtotime("+3 days")),
                          date('Y-m-d', strtotime("+4 days")) ];
        TForm::sendData('my_form', $data);
    }
    
    /**
     * Fill data
     */
    public static function onClearFill($param)
    {
    
        TFieldList::clearRows('my_field_list');
        TFieldList::addRows('my_field_list', 4);
        
        $data = new stdClass;
        $data->combo  = [ 1,2,3,4,5 ];
        $data->text   = [ 'Part. One', 'Part. Two', 'Part. Three', 'Part. Four', 'Part. Five' ];
        $data->number = [ '10,10','20,20', '30,30', '40,40', '50,50' ];
        $data->date   = [ date('Y-m-d'),
                          date('Y-m-d', strtotime("+1 days")),
                          date('Y-m-d', strtotime("+2 days")),
                          date('Y-m-d', strtotime("+3 days")),
                          date('Y-m-d', strtotime("+4 days")) ];
        
        TForm::sendData('my_form', $data, false, true, 2000); // 200 ms of timeout after recreate rows!
    }
    
    /**
     * Save simulation
     */
    public static function onSave($param)
    {
        // show form values inside a window
        $win = TWindow::create('test', 0.6, 0.8);
        $win->add( '<pre>'.str_replace("\n", '<br>', print_r($param, true) ).'</pre>'  );
        $win->show();
    }
}
