<?php
/**
 * TimelineView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TimelineView extends TPage
{
    public function __construct()
    {
        parent::__construct();
		
		$timeline = new TTimeline;
		
		$obj1 = (object) [ 'name' => 'AAA' ];
		$obj2 = (object) [ 'name' => 'BBB' ];
		$obj3 = (object) [ 'name' => 'CCC' ];
		$obj4 = (object) [ 'name' => 'DDD' ];
		$obj5 = (object) [ 'name' => 'EEE' ];
		
		$timeline->addItem(1, 'Event {id}',  'This is the event id: <b>{id}</b> name: <b>{name}</b>', '2017-12-11 12:01:00',  'fa:arrow-left bg-green',  'left',  $obj1 );
		$timeline->addItem(2, 'Event {id}',  'This is the event id: <b>{id}</b> name: <b>{name}</b>', '2017-12-11 12:02:00',  'fa:arrow-left bg-green',  'left',  $obj2 );
		$timeline->addItem(3, 'Event {id}',  'This is the event id: <b>{id}</b> name: <b>{name}</b>', '2017-12-13 12:03:00',  'fa:arrow-right bg-blue',  'right', $obj3 );
		$timeline->addItem(4, 'Event {id}',  'This is the event id: <b>{id}</b> name: <b>{name}</b>', '2017-12-14 12:04:00',  'fa:arrow-right bg-blue',  'right', $obj4 );
		
		$timeline->setUseBothSides();
		$timeline->setTimeDisplayMask('dd/mm/yyyy');
		$timeline->setFinalIcon( 'fa:flag-checkered bg-red' );
		
		$display_condition = function( $object = false ) {
			if( in_array($object->id, [2,3]))
			{
				return true;
			}
			return false;
		};
		
		$action1 = new TAction([$this, 'onAction1'], ['id' => '{id}', 'name' => '{name}']);
		$action2 = new TAction([$this, 'onAction2'], ['id' => '{id}', 'name' => '{name}']);
		
		$action1->setProperty('btn-class', 'btn btn-primary');
		$action2->setProperty('btn-class', 'btn btn-danger');
		
		$timeline->addAction($action1, 'Action 1', 'fa:bus', $display_condition );
		$timeline->addAction($action2, 'Action 2', 'fa:bus');
		
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($timeline);

        parent::add($vbox);
    }
    
    /**
     * Action 1
     */
    public static function onAction1($param)
    {
        new TMessage('info', 'Action1 on Event '. '<b>' . $param['id'] . '-' . $param['name'] . '</b>' );
    }
    
    /**
     * Action 2
     */
    public static function onAction2($param)
    {
        new TMessage('info', 'Action2 on Event '. '<b>' . $param['id'] . '-' . $param['name'] . '</b>' );
    }
}