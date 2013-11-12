<?php 
$children = !empty($data['children'][0]['Category']['id']) ? implode(',', Set::extract('/Category/id', $data['children'])) : null;

echo $this->Form->input('Category.Category.'.$data['Category']['parent_id'], array('data-depth' => $depth, 'data-children' => $children, 'data-parent' => $data['Category']['parent_id'], 'purchasable' => true, 'combine' => array('{n}.Category.id', '{n}.Category.name'),  'options' => array($data), 'type' => 'radio', 'value' => $data['Category']['id']));
