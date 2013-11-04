<div class="row-fluid">
    <div class="span8">
    	 <fieldset>
	    	<legend>Classifieds by category</legend>
	    	<?php echo $this->Tree->generate($categories, array('model' => 'Category', 'alias' => 'item_text', 'class' => 'categoriesList', 'id' => 'categoriesList', 'element' => 'Categories/item', 'elementPlugin' => 'classifieds')); ?>
			<!-- <ul>
			<?php foreach ($categories as $category) : ?>
				<li>
				  	<h3>
				  		<?php echo $this->Html->link($category['Category']['name'], array('action' => 'index', '?' => array('categories' => $category['Category']['name']))) ?>
				  		<small>(<?php echo $category['Category']['record_count'] ?>)</small>
				  	</h3>
			  		<p><?php echo $category['Category']['description'] ?></p>
			  </li>
			<?php endforeach; ?>
			</ul> -->
		</fieldset>
    </div>
    
	<div class="span4">
		<?php echo $this->Form->create('Category'); ?>
	    <?php echo $this->Form->hidden('Category.model', array('value' => 'Classified')); ?>
	    <fieldset>
	    	<legend>Add New Category</legend>
	        <?php echo $this->Form->input('Category.parent_id', array('empty' => '-- Optional --', 'options' => $parentCategories)); ?>
	        <?php echo $this->Form->input('Category.name'); ?>
	        <?php echo $this->Form->end('Submit'); ?>
	    </fieldset>
	</div>
</div>
<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'classifieds', 'action' => 'dashboard')),
			)
		),
    // array(
		// 'heading' => 'Add Category',
		// 'items' => array(
			// $this->Form->create('Category').$this->Form->hidden('Category.model', array('value' => 'Product')).$this->Form->input('Category.parent_id', array('empty' => '-- Optional --', 'options' => $parentCategories)).$this->Form->input('Category.name').$this->Form->end('Submit'),
			// )
		// ),
	)));
