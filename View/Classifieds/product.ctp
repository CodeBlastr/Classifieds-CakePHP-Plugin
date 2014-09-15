<?php
/**
*@var $this View
**/
?>
<?php
/**
 * Products Admin Add View
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
?>
    <div class="productAdd form">
        <?php echo $this->Form->create('Product', array('type' => 'file','plugin'=>'Products','url'=>'/admin/products/products/add'));
        echo $this->Form->hidden('Product.is_virtual', array('value' => 1));
        ?>
        <fieldset>
            <?php
            echo $this->Form->input('Product.name', array('label' => 'Display Name'));
            echo $this->Form->hidden('Product.model', array('value'=>'Classified'));
            echo $this->Form->input('Product.data.days', array('label' => 'Expiration Days','required'=>'required','value'=>30,'min'=>1,'step'=>1,'type'=>'number'));
            echo $this->Form->input('Product.price', array('label' => 'Retail Price <small><em>(ex. 0000.00)</em></small>' ,'type' => 'number', 'step' => '0.01', 'min' => '0', 'max' => '99999999999'));
            echo CakePlugin::loaded('Media') ? $this->Element('Media.selector', array('multiple' => true)) : null;
            echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?'));
            ?>

        </fieldset>


        <?php echo $this->Form->end('Submit'); ?>
    </div>

    <script type="text/javascript">

        $('#addCat').click(function(e){
            e.preventDefault();
            $('#anotherCategory').show();
        });

        $('#priceID').click(function(e){
            e.preventDefault();
            action = '<?php echo $this->Html->url(array('plugin' => 'products',
					'controller'=>'product_prices', 'action'=>'add', 'admin'=>true))?>';
            $("#ProductAddForm").attr("action" , action);
            $("#ProductAddForm").submit();
        });
        function rem($id) {
            $('#div'+$id).remove();
        }

        $(document).ready( function(){
            if($('input.shipping_type:checked').val() == 'FIXEDSHIPPING') {
                $('#ShippingPrice').show();
            } else {
                $('#ShippingPrice').hide();
            }
        });

        var shipTypeValue = null;
        $('input.shipping_type').click(function(e){
            shipTypeValue = ($('input.shipping_type:checked').val());
            if(shipTypeValue == 'FIXEDSHIPPING') {
                $('#ShippingPrice').show();
            } else {
                $('#ShippingPrice').hide();
            }
        });

    </script>


<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
        'heading' => 'Products',
        'items' => array(
            $this->Html->link(__('Dashboard'), array('controller' => 'products', 'action' => 'dashboard')),
            $this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
        )
    )
)));