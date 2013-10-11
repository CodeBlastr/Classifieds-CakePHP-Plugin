<div class="classifieds form">
	<?php echo $this->Form->create('Classifieds.Classified', array('type' => 'file')); ?>
	<div class="row-fluid">
		<div class="span4">
			<?php echo $this->Form->input('Classified.title', array('type' => 'text')); ?>
		</div>
		<div class="span4">
			<?php echo $this->Form->input('Classified.expire_date', array('label' => 'Expiration Date', 'type' => 'datepicker', 'class' => 'input-medium')); ?>
		</div>
		<div class="span4">
			<?php echo $this->Form->input('GalleryImage.filename', array('type' => 'file')); ?>
		</div>
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
		<?php /*<div class="span3">
			<?php if (CakePlugin::loaded('Categories')) : ?>
				<?php //echo $this->Form->input('Category.Category', array('type' => 'radio', 'legend' => false, 'class' => 'input-medium', 'purchasable' => true, 'combine' => array('{n}.Category.id', '{n}.Category.name'), 'options' => $categories, 'limit' => 3)); ?>
			<?php endif; ?>
		</div> */ ?>
		
		<div class="span3">
			<?php echo $this->Form->input('Classified.city', array('type' => 'text')); ?>
		</div>
		<div class="span3">
			<?php echo $this->Form->input('Classified.state', array('empty' => '- choose -', 'options' => states(), 'class' => 'input-medium')); ?>
		</div>
		<div class="span3">
			<?php echo $this->Form->input('Classified.zip', array('type' => 'text')); ?>
		</div>
	</div>
	
	<div class="accordion row-fluid" id="catTest"></div>
	<?php echo $this->Tree->generate($categories, array('model' => 'Category', 'alias' => 'item_text', 'class' => 'categoriesList', 'id' => 'categoriesList', 'element' => 'Categories/input', 'elementPlugin' => 'classifieds')); ?>
	
	<?php //echo $this->Form->input('Classified.weight', array('type' => 'text')); ?>
	<?php echo $this->Form->end('Save'); ?>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$('ul').css('list-style-type', 'none');
		$('.categoriesList').hide();
				
		// put our existing radio buttons into an object grouped by depth and parent
		var inputs = {};
		var key;
		var parent;
		$.each($('input[type=radio]'), function(index, value) {
			key = $(value).attr('data-depth');
			inputs[key] = new Array();
		});
		$.each($('input[type=radio]'), function(index, value) {
			key = $(value).attr('data-depth');
			parent = $(value).attr('data-parent') ? $(value).attr('data-parent') : 'parent';
			inputs[key][parent] = new Array();
		});		
		$.each($('input[type=radio]'), function(index, value) {
			key = $(value).attr('data-depth');
			parent = $(value).attr('data-parent') ? $(value).attr('data-parent') : 'parent';
			inputs[key][parent][index] = $(value).parent().html();
		});
		// end building the big multi-dimensional array
		
		
		// create the accordion
		var name = '';
		var key = '';
		var parent = '';
		
		$.each(inputs, function(depth, obj) {
			for (var key in obj) {
				var value = obj[key];
				$('#catTest').append('<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#catTest" data-parent-label="' + parent + '" href="#collapse-' + key + '">Category</a></div><div id="collapse-' + key + '" class="accordion-body collapse"><div class="accordion-inner depth-' + key + '"></div></div></div>')
				if (value) {
					$('#catTest .accordion-inner.depth-' + key).append(value.join(''));
				}
				//name = value.pop().replace('input', 'span') + '</span>';
				parent = key;
			}
		});
		
		// give accordion blocks the proper label
		var parent = '';
		$.each($('#catTest input[type=radio]'), function(index, value) {
			parent = $(this).attr('data-parent');
			label = $('.accordion-inner input[value=' + parent + ']').next().text();
			$('a[href=#collapse-' + parent + ']').text(label);
		});
		
		
		$('.accordion-group').hide();
		$('.accordion-group:first-child').show();
		$('input[type="radio"]').change(function() {
			var child = $(this).attr('data-first-child');
			var me = $(this).parent().parent().parent().index();
			$('.accordion-group:gt(' + me + ')').hide();
			$(this).parent().parent().parent().show();
			$('input[data-parent=' + child + ']').parent().parent().parent().show();
		});
		
		
		// inputs[0][0] = automobiles
		// inputs[1][0] = make
		// inputs[2][0] = dodge
		// inputs[2][1] = ford
		// inputs[3][0] = model
		// inputs[3][1] = model
		// inputs[4][0] = caravan
		// inputs[0][1] = electronics
		// inputs[0][2] = equipment & ag		
		
		
		// $('.categoriesList li div').hide();
		// $('input[type=radio]').change(function() {
			// var parentValue = $(this).val();
			// console.log(parentValue);
			// $('.categoriesList li div').hide();
			// //$('.categoriesList li[data-parent=' + parentValue + ']').show();
			// //$('.categoriesList li ul li').hide();
			// $('.categoriesList li[data-parent=' + parentValue + '] ul li div').show();
			// $('.categoriesList li[data-parent=' + parentValue + '] ul li ul li div').hide();
		// });
// 		
		// $('select').change(function(){
			// var parentValue = $(this).val();
			// console.log(parentValue);
			// //$('.categoriesList li div').hide();
			// //$('.categoriesList li[data-parent=' + parentValue + ']').show();
			// //$('.categoriesList li ul li').hide();
			// $('.categoriesList li ul li ul li div').hide();
			// $('.categoriesList li[data-parent=' + parentValue + '] ul li ul li div').hide();
			// $('.categoriesList li[data-parent=' + parentValue + '] ul li div').show();
		// });
	});
	
</script>
