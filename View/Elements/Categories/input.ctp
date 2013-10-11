<?php 
// if (!empty($data['children'])) {
	$firstChild = !empty($data['children'][0]['Category']['id']) ? $data['children'][0]['Category']['id'] : null;
	// $options = Set::combine($data['children'], '{n}.Category.id', '{n}.Category.name');
	// echo $this->Form->input('Category.Category.'.$depth, array('empty' => '--Select--', 'options' => $options, 'type' => 'radio', 'label' => $data['Category']['name'], 'value' => $data['Category']['id']));
// } else {
	
	echo $this->Form->input('Category.Category.'.$depth, array('data-depth' => $depth, 'data-first-child' => $firstChild, 'data-parent' => $data['Category']['parent_id'], 'options' => array($data['Category']['id'] => $data['Category']['name']), 'type' => 'radio', 'value' => $data['Category']['id']));
	//debug($data);
//}

//$this->Tree->addItemAttribute('data-parent', false, $data['Category']['id']);
?>

<?php
// echo __('<span class="badge badge-success">%s</span> %s %s %s', 
	// $data['Category']['record_count'],
	// $this->Html->link($data['Category']['name'], array('plugin' => 'classifieds', 'controller' =>'classifieds', 'action' => 'index', '?' => array('categories' => $data['Category']['name']))), 
	// $this->Html->link('<i class="icon-edit"></i>', array('plugin' => 'categories', 'controller' =>'categories', 'action' => 'edit', $data['Category']['id']), array('escape' => false)), 
	// $this->Form->postLink('<i class="icon-remove-sign"></i>', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'delete', $data['Category']['id']), array('escape' => false), __('Are you sure?')));