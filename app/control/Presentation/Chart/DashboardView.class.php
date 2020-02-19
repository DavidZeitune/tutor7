<?php
/**
 * Chart
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DashboardView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        
        $div = new TElement('div');
        $div->class = "row";
        
        $indicator1 = new THtmlRenderer('app/resources/info-box.html');
        $indicator2 = new THtmlRenderer('app/resources/info-box.html');
        
        $indicator1->enableSection('main', ['title'     => 'Access',
                                           'icon'       => 'sign-in-alt',
                                           'background' => 'green',
                                           'value'      => 100 ] );
        
        $indicator2->enableSection('main', ['title'      => 'Users',
                                            'icon'       => 'user',
                                            'background' => 'orange',
                                            'value'      => 200 ] );

        $div->add( $i1 = TElement::tag('div', $indicator1) );
        $div->add( $i2 = TElement::tag('div', $indicator2) );
        
        $div->add( $g1 = new BarChartView(false) );
        $div->add( $g2 = new LineChartView(false) );
        $div->add( $g3 = new ColumnChartView(false) );
        $div->add( $g4 = new PieChartView(false) );
        
        $i1->class = 'col-sm-6';
        $i2->class = 'col-sm-6';
        $g1->class = 'col-sm-6';
        $g2->class = 'col-sm-6';
        $g3->class = 'col-sm-6';
        $g4->class = 'col-sm-6';
        
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($div);
        
        parent::add($vbox);
    }
}
