<?php

class MCategory extends KBaseMasterComponent
{
	protected $_entity = 'Category';

	public function handlers()
	{
		return array(
			'index' => array(
				'onMakeGrid' => 'makeGrid',
				'onBeforeGetList' => 'beforeGetList',
			),
			'create, update' => array(
				'onMainForm' => 'mainForm',
			)
		);
	}

	public function beforeGetList($event)
	{
		$c = $event->criteria;
		$c->select = 'Id, ParentId, Lft, Rgt, TableName, CONCAT(REPEAT("&ndash; ",`level`-1), Title) as Title';
		$c->order = 'Lft';
	}

	public function makeGrid(KEvent $event)
	{
		$event->grid['columns'] += array(
			array('name' => 'Title', 'type' => 'raw'),
			'TableName',
			'Id',
			'ParentId',
			'Lft',
			'Rgt',
		);
	}

	public function mainForm(KEvent $event)
	{
		if (empty($_POST))
			$this->_model->ParentId = $this->_model->Id;
		KForm::append($event->form, array(
			'companyTab' => KForm::tab('Company', array(
				'basicFieldset' => KForm::fieldset('Basic info', array(
					'Title' => array('type' => 'text'),
					'TableName' => array('type' => 'text'),
					'ParentId' => array('type' => 'dropdownlist', 'items' => $this->getItems(), 'encode' => false),
				)),
			))
		));
	}

	public function getItems() {
		$c = new CDbCriteria(array('select' => 'Id, Title, Level', 'order' => 'Lft'));
		$i = $this->_model->getArray($c);
		$tree = array();
		$space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$corner = '|__';
		foreach ($i as $k => $r)
		{
			$tab = str_repeat($space, $r['Level'] - 1);
			if ($k > 0) {
				if ($this->_model->Id == $r['Id']) {
					$tree[$this->_model->Id] = $tab . '-->' . $r['Title'];
				}
				else
				{
					$tree[$r['Id']] = $tab . $corner . 'after - ' . $r['Title'];
					$tree[-$r['Id']] = $tab . $space . $corner . 'child of - ' . $r['Title'];
				}
			} else
				$tree[-$r['Id']] = 'At the top';


		}
		return $tree;
	}

	public function save()
	{

		if ($result = ($this->scenario() == 'create' ? $this->_owner->hasAccess('create') : $this->_owner->hasAccess('update')))
		{
			if ($this->_model->parentid != $this->_model->Id)
			{
				$target = $this->_model->findByPk(abs($this->_model->ParentId));
				$parentId = false;
				if ($target) {
					if ($this->_model->getIsNewRecord()) {
						if ($this->_model->ParentId < 0) {
							$parentId = abs($this->_model->ParentId);
							$this->_model->ParentId = $parentId;
							$this->_model->prependTo($target);
						}
						else {
							$parent = $target->getParent();
							$parentId = $parent->Id;
							$this->_model->insertAfter($target);
						}
					} else
					{
						if (!$target->isDescendantOf($this->_model)) {
							if ($this->_model->ParentId < 0) {
								$parentId = $target->Id;
								$this->_model->moveAsFirst($target);
							} else {
								$parentId = $target->getParent()->Id;
								$this->_model->moveAfter($target);
							}
						}
					}
				}
				$attributes = array('Title' => $this->_model->Title, 'TableName' => $this->_model->TableName);
				if ($parentId !== false)
					$attributes += array('ParentId' => $parentId);
				$this->_model->updateByPk($this->_model->Id, $attributes);
			}
		}
		return true;
	}

	public function delete()
	{
		if ($result = $this->_owner->hasAccess('delete')) {
			if ($this->_model->descendants()->count() != 0)
				return false;
			$event = $this->getEvent();
			$this->raiseEvent('onBeforeDelete', $event);
			if ($result = $this->_model->deleteNode())
				$this->raiseEvent('onAfterDelete', $event);
		}
		return $result;
	}

	public function deleteAll($identifiers)
	{
		$failed = array();
		if (count($identifiers) > 1)
			$failed = $identifiers;
		else
		{
			$this->loadByPk($identifiers[0]);
			if (!$this->delete()) {
				array_push($failed, $identifiers[0]);
			}
		}
		return $failed;
	}
}