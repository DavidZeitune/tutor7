<?php
/**
 * FormNestedBuilderView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormNestedBuilderView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_builder');
        $this->form->setFormTitle(_t('Bootstrap nested form'));
        $this->form->setFieldSizes('100%');
        
        $this->form->appendPage('Basic');
        
        $code         = new TEntry('code');
        $name         = new TEntry('name');
        $doc          = new TEntry('doc');
        $gender       = new TCombo('gender');
        $driver       = new TEntry('driver');
        $birthdate    = new TDate('birthdate');
        $status       = new TCombo('status');
        $homephone    = new TEntry('homephone');
        $cellphone    = new TEntry('cellphone');
        $street       = new TEntry('street');
        $number       = new TEntry('number');
        $neighborhood = new TEntry('neighborhood');
        $city         = new TDBUniqueSearch('city', 'samples', 'City', 'id', 'name');
        $state        = new TCombo('state');
        $postal       = new TEntry('postal');
        
        $city->setMinLength(1);
        $city->setMask('({id}) <b>{name}</b> - {state->name}');
        $status->addItems( ['S' => 'Single', 'C' => 'Commited'] );
        $gender->addItems( ['F' => 'Female', 'M' => 'Male'] );
        $state->addItems(['AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona',
                          'CA' => 'California', 'CO' => 'Colorado']);
        $postal->setMask('99.999-999', true);
        $doc->setMask('999.999.999-99');
        $birthdate->setMask('dd/mm/yyyy');
        $homephone->setMask('(99)9999-99999');
        $cellphone->setMask('(99)9999-99999');
        $birthdate->setDatabaseMask('yyyy-mm-dd');
        $state->enableSearch();
        
        $row = $this->form->addFields( [ new TLabel('Code'),     $code ],
                                       [ new TLabel('Name'),     $name ],
                                       [ new TLabel('Gender'),   $gender ],
                                       [ new TLabel('Status'),   $status ] );
        $row->layout = ['col-sm-2', 'col-sm-6', 'col-sm-2', 'col-sm-2' ];
        
        $row = $this->form->addFields( [ new TLabel('Driver lic.'),  $driver ],
                                       [ new TLabel('Document'),     $doc ],
                                       [ new TLabel('Birthdate'),    $birthdate ],
                                       [ new TLabel('Home phone'),   $homephone ],
                                       [ new TLabel('Cell phone'),   $cellphone ]);
        $row->layout = ['col-sm-2', 'col-sm-3', 'col-sm-3', 'col-sm-2', 'col-sm-2' ];
        
        $subform = new BootstrapFormBuilder;
        $subform->setFieldSizes('100%');
        $subform->setProperty('style', 'border:none');
        
        $subform->appendPage( 'Address data' );
        $row = $subform->addFields( [ new TLabel('Street.'),      $street ],
                                       [ new TLabel('Number'),       $number ],
                                       [ new TLabel('Neighborhood'), $neighborhood ] );
        $row->layout = ['col-sm-6', 'col-sm-2', 'col-sm-4' ];
        
        $row = $subform->addFields( [ new TLabel('City.'),  $city ],
                                       [ new TLabel('State'),  $state ],
                                       [ new TLabel('Postal'), $postal ] );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3' ];
        
        $subform->appendPage( 'Other data' );
        $row = $subform->addFields( [ new TLabel('Test1'), $t1 = new TEntry('test1') ],
                                       [ new TLabel('Test2'), $t2 = new TEntry('test2') ] );
        $row->layout = ['col-sm-4', 'col-sm-8' ];
        
        $row = $subform->addFields( [ new TLabel('Test3'), $text = new TText('text') ] );
        $row->layout = ['col-sm-12' ];
        
        $this->form->addContent( [$subform] );
        
        $this->form->addAction('Send', new TAction(array($this, 'onSend')), 'far:check-circle green');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Post data
     */
    public function onSend($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
