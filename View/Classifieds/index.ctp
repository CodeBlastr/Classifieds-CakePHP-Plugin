<div class="classifieds index">
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

