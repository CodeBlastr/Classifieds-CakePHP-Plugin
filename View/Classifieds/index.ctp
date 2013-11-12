<div class="classifieds index">
	<ul class="breadcrumb">
	<?php echo !empty($this->request->query['categories']) ? '<li><a href="/classifieds">All Listings</a> <span class="divider">/</span></li>' : null; ?>
	<?php foreach($categories as $category) : ?>
		<?php if (!empty($category['ChildCategory'])) : ?>
		<li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['Category']['name']; ?> <span class="caret"></span></a>
			<ul class="dropdown-menu">
				<?php foreach ($category['ChildCategory'] as $key => $child) : ?>
					<?php if (!empty($child[0])) : ?>
						<li class="nav-header"><?php echo $child['name']; ?></li>
						<?php unset($child['name']); $attributes = Set::extract('/name', $child); ?>
						<?php foreach ($attributes as $attribute) : ?>
							<li><a href="/classifieds?categories=<?php echo $attribute; ?>"><?php echo $attribute; ?></a></li>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
		<li><a href="/classifieds?categories=<?php echo $category['Category']['name']; ?>"><?php echo $category['Category']['name']; ?></a> <span class="divider">/</span>
		<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
	<?php if(!empty($classifieds)): ?>
		<?php foreach($classifieds as $classified): ?>
		<div class="row-fluid">
			<div class="span3">
				<h4><?php echo $classified['Classified']['title']; ?></h4>
				<div class="image"><?php echo $this->Media->display($classified['Media'][0], array('width' => 150, 'height' => 150)); ?></div>
				<p><?php echo $classified['Classified']['price']; ?></p>
				<p><?php echo $classified['Classified']['description']; ?></p>
				<p><?php echo $classified['Classified']['condition']; ?></p>
				<p><?php echo $classified['Classified']['data']; ?></p>
			</div>
			<div class="span3">
				<address>
					<p><?php echo $classified['Classified']['city']; ?>, <?php echo $classified['Classified']['state']; ?> <?php echo $classified['Classified']['zip']; ?></p>
				</address>
			</div>
			<div class="span3">
				<p><?php echo $classified['Classified']['payment_terms']; ?></p>
				<p><?php echo $classified['Classified']['shipping_terms']; ?></p>
				<p><?php echo $classified['Classified']['weight']; ?></p>
				<p><?php echo $classified['Classified']['posted_date']; ?></p>
				<p><?php echo $classified['Classified']['expire_date']; ?></p>
			</div>
			<div class="span3 action-links">
				<?php echo $this->Html->link('View', array('action' => 'view', $classified['Classified']['id']), array('class' => 'btn')); ?>
				<?php echo $this->Html->link('Edit', array('action' => 'edit', $classified['Classified']['id']), array('class' => 'btn')); ?>
				<?php echo $this->Form->postLink('Delete', array('action' => 'delete', $classified['Classified']['id']), array('class' => 'btn'), ('Are you sure you want to delete '.$classified['Classified']['title'].'?')); ?>
			</div>
		</div>	
		<?php endforeach; ?>
	<?php else: ?>
		<h4>No Results Found</h4>
	<?php endif; ?>
</div>

<?php echo $this->Element('paging'); ?>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Classifieds',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'classifieds', 'action' => 'dashboard')),
			$this->Html->link(__('Add'), array('admin' => true, 'controller' => 'classifieds', 'action' => 'add')),
			)
		),
	)));

