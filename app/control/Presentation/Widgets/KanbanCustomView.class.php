<?php
/**
 * KanbanView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Artur Comunello
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class KanbanCustomView extends TPage
{
	private $form;
	
	public function __construct()
	{
		parent::__construct();
		
		$kanban = new TKanban;
		
		$stages = [];
		$stages[] = [ 'id' => 1, 'title'=> 'stage 1'];
		$stages[] = [ 'id' => 2, 'title'=> 'stage 2'];
		$stages[] = [ 'id' => 3, 'title'=> 'stage 3'];
		
		foreach ($stages as $stage)
		{
		    $kanban->addStage($stage['id'], $stage['title']);
		}
		
		$items = [];
		$items[] = [ 'id' => 101, 'stage_id' => 1, 'title' => 'item 1.1', 'content' => 'item 1.1 content', 'color' => '#FF1818'];
		$items[] = [ 'id' => 102, 'stage_id' => 1, 'title' => 'item 1.2', 'content' => 'item 1.2 content', 'color' => '#57D557'];
		$items[] = [ 'id' => 201, 'stage_id' => 2, 'title' => 'item 2.1', 'content' => 'item 2.1 content', 'color' => '#5950F1'];
		$items[] = [ 'id' => 202, 'stage_id' => 2, 'title' => 'item 2.2', 'content' => 'item 2.2 content', 'color' => '#57D557'];
		$items[] = [ 'id' => 301, 'stage_id' => 3, 'title' => 'item 3.1', 'content' => 'item 3.1 content', 'color' => '#CC2EC9'];
		$items[] = [ 'id' => 302, 'stage_id' => 3, 'title' => 'item 3.2', 'content' => 'item 3.2 content', 'color' => '#5950F1'];
		
		foreach ($items as $key => $item)
		{
			$kanban->addItem($item['id'], $item['stage_id'], $item['title'], $item['content'], $item['color']);
		}
		
		$kanban->addStageAction('Action 1', new TAction([$this, 'onEditStage']),   'far:edit blue fa-fw');
		$kanban->addStageAction('Action 2', new TAction([$this, 'onDeleteStage']), 'far:trash-alt red fa-fw');
		
		$kanban->addItemAction('Edit',   new TAction([$this, 'onItemEdit']),   'far:edit blue fa-fw');
		$kanban->addItemAction('Delete', new TAction([$this, 'onItemDelete']), 'far:trash-alt red fa-fw');
		
		$kanban->setTemplatePath('app/resources/card.html');
		$kanban->setItemDropAction(new TAction([$this, 'onUpdateItemDrop']));
		$kanban->setStageDropAction(new TAction([$this, 'onUpdateStageDrop']));
		
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($kanban);

        parent::add($vbox);
	}
	
    /**
     * Update stage on drop
     */
	public static function onUpdateStageDrop($param)
	{
		new TMessage('info', '<b>onUpdateStageDrop</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
	
    /**
     * Update item on drop
     */
	public static function onUpdateItemDrop($param)
	{
		new TMessage('info', '<b>onUpdateItemDrop</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
	
    /**
     * Stage edit action
     */
	public static function onEditStage($param = NULL)
	{
		new TMessage('info', '<b>onEditStage</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
	
    /**
     * Stage delete action
     */
	public static function onDeleteStage($param = NULL)
	{
		new TMessage('info', '<b>onDeleteStage</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
	
    /**
     * Item edit action
     */
	public static function onItemEdit($param = NULL)
	{
		new TMessage('info', '<b>onItemEdit</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
	
    /**
     * Item delete action
     */
	public static function onItemDelete($param = NULL)
	{
		new TMessage('info', '<b>onItemDelete</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
}
