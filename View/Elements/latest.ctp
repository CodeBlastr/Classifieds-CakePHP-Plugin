<?php $this->Classified = $this->Helpers->load('Classifieds.Classified'); ?>
<?php $classifieds = $this->Classified->latestAds(); ?>


<div class="list-group">
	<?php foreach ($classifieds as $classified) : ?>
		<a class="list-group-item" href="/classifieds/classifieds/view/<?php echo $classified['Classified']['id']; ?>">
			<?php echo $classified['Classified']['title']; ?><br>
			<?php echo ZuhaInflector::pricify($classified['Classified']['price'], array('currency' => 'USD')); ?>, <?php echo $classified['Classified']['state']; ?>
		</a>
	<?php endforeach; ?>
</div>
