<?php 
if (!empty($data['children'])) { 
	$options = Set::combine($data['children'], '{n}.Category.id', '{n}.Category.name');
	echo $this->Form->input('Category.Category.'.$depth, array('empty' => '--Select--', 'options' => $options, 'type' => 'select', 'label' => $data['Category']['name'], 'value' => $data['Category']['id']));
} else {
	
	//debug($data);
}

$this->Tree->addItemAttribute('data-parent', false, $data['Category']['id']);
?>

<?php
// echo __('<span class="badge badge-success">%s</span> %s %s %s', 
	// $data['Category']['record_count'],
	// $this->Html->link($data['Category']['name'], array('plugin' => 'classifieds', 'controller' =>'classifieds', 'action' => 'index', '?' => array('categories' => $data['Category']['name']))), 
	// $this->Html->link('<i class="icon-edit"></i>', array('plugin' => 'categories', 'controller' =>'categories', 'action' => 'edit', $data['Category']['id']), array('escape' => false)), 
	// $this->Form->postLink('<i class="icon-remove-sign"></i>', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'delete', $data['Category']['id']), array('escape' => false), __('Are you sure?')));