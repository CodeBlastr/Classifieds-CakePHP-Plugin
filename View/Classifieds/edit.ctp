<div class="classifieds form">
	<?php echo $this->Form->create('Classifieds.Classified'); ?>
		<?php echo $this->Form->input('Classified.id'); ?>
		<div class="row-fluid">
			<div class="span4">
				<?php echo $this->Form->input('Classified.title', array('type' => 'text')); ?>
			</div>
			<div class="span4">
				<?php echo $this->Form->input('Classified.posted_date', array('type' => 'datetimepicker')); ?>
			</div>
			<div class="span4">
				<?php echo $this->Form->input('Classified.expire_date', array('type' => 'datetimepicker')); ?>
			</div>
		</div>
		
		<div class="row-fluid">
			<?php if(CakePlugin::loaded('Media')) : ?>
				<?php echo $this->Element('Media.media_selector', array('media' => $this->request->data['Media'], 'multiple' => true)); ?>
			<?php endif; ?>
		</div>
		
		<div class="row-fluid">
			<?php echo $this->Form->input('Classified.description', array('type' => 'textarea')); ?>
		</div>		
		
		<div class="row-fluid">
			<div class="span3">
				<?php echo $this->Form->input('Classified.price', array('type' => 'text')); ?>
			</div>
			<div class="span3">
				<?php echo $this->Form->input('Classified.condition', array('type' => 'text')); ?>
			</div>
			<div class="span3">
				<?php echo $this->Form->input('Classified.payment_terms', array('type' => 'text')); ?>
			</div>
			<div class="span3">
				<?php echo $this->Form->input('Classified.shipping_terms', array('type' => 'text')); ?>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span3">
				<?php if (CakePlugin::loaded('Categories')) : ?>
					<?php // needs some work to be able to use (to work with the /classifieds/classifieds/post category picker)
					//echo $this->Form->input('Category.Category', array('type' => 'select', 'options' => $categories, 'multiple' => 'checkbox', 'limit' => 3)); ?>
				<?php endif; ?>
			</div>
			<div class="span3">				
				<?php echo $this->Form->input('Classified.city', array('type' => 'text')); ?>
			</div>
			<div class="span3">				
				<?php echo $this->Form->input('Classified.state', array('empty' => '- choose -', 'options' => states())); ?>
			</div>
			<div class="span3">			
				<?php echo $this->Form->input('Classified.zip', array('type' => 'text')); ?>
			</div>				
		</div>
	<?php //echo $this->Form->input('Classified.weight', array('type' => 'text')); ?>
	<?php echo $this->Form->end('Save'); ?>
	
	<?php echo $this->Form->create('TransactionItem', array('url' => array('plugin' => 'transactions', 'controller'=>'transaction_items', 'action'=>'add'))); ?>
		<?php echo $this->Form->hidden('TransactionItem.quantity' , array('class' => 'span', 'label' => false, 'value' => 1, 'min' => $minQty, 'max' => $maxQty)); ?>
		<?php echo $this->Form->hidden('TransactionItem.name' , array('value' => $this->request->data('Classified.title'))); ?>
		<?php echo $this->Form->hidden('TransactionItem.model' , array('value' => 'Classified')); ?>
		<?php echo $this->Form->hidden('TransactionItem.foreign_key' , array('value' => $this->request->data('Classified.id'))); ?>
		<?php echo $this->Form->hidden('TransactionItem.price' , array('value' => '5.00')); ?>
		<?php echo $this->Form->hidden('TransactionItem.cart_max' , array('value' => $maxQty)); ?>
		<?php echo $this->Form->hidden('TransactionItem.cart_min' , array('value' => $minQty)); ?>
		
	 <?php //echo $this->Element('payment_type', array(), array('plugin' => 'products')); ?>
	
	 <?php echo $this->Form->end('Make Featured'); ?>
	
</div>
