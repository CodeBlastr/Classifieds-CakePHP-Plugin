<?php echo $this->Html->script('http://code.highcharts.com/highcharts.js', array('inline' => false)); ?>
<?php echo $this->Html->script('http://code.highcharts.com/modules/exporting.js', array('inline' => false)); ?>

<div class="products row-fluid">
    <div class="span8 pull-left first">
        <ul class="nav nav-tabs" id="myTab">
            <li><a href="#today" data-toggle="tab">Today</a></li>
            <li><a href="#thisWeek" data-toggle="tab">This Week</a></li>
            <li><a href="#thisMonth" data-toggle="tab">This Month</a></li>
            <li><a href="#thisYear" data-toggle="tab">This Year</a></li>
            <li><a href="#allTime" data-toggle="tab">All Time</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade" id="today">
                <div class="row-fluid">
                    <div class="alert alert-success clearfix">
                        <h3 class="span6 pull-left"> <?php echo $statsPostedToday['count']; ?> Posted Today </h3>
                    </div>

                    <?php
                    // vars for chart
                    $hour = array_fill(0, 24, 0);
                    foreach ($statsPostedToday as $post) {
                        if ($post['Classified']) {
                            $hourKey = (int) date('H', strtotime($post['Classified']['created']));
                            $hour[$hourKey]++;
                        }
                    } ?>
                    <script type="text/javascript">
                    $(function () {
                        $('#myTab a:first').tab('show');
                    });
                    var chart;
                    $(document).ready(function() {
                        chart = new Highcharts.Chart({
                            chart: {
                                renderTo: 'ordersToday',
                                type: 'spline'
                            },
                            credits: false,
                            title: {
                                text: false
                            },
                            subtitle: {
                                text: false
                            },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: { // don't display the dummy year
                                    month: '%e. %b',
                                    year: '%b'
                                }
                            },
                            yAxis: {
                                title: {
                                    text: false
                                },
                                min: 0
                            },
                            tooltip: {
                                formatter: function() {
                                        return '<b>'+ this.series.name +'</b><br/>'+
                                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' m';
                                }
                            },
        
                            series: [{
                                name: 'Posts',
                                // Define the data points. All series have a dummy year
                                // of 1970/71 in order to be compared on the same x axis. Note
                                // that in JavaScript, months start at 0 for January, 1 for February etc.
                                data: [
                                <?php
                                $i = 0;
                                while ($i < 24) { ?>
                                    [<?php echo $i ?>,   <?php echo $hour[$i] ? $hour[$i] : 0; ?>],
                                <?php ++$i; } ?>
                                ]
                            }]
                        });
                    });
                    </script>
                    <div id="ordersToday" style="min-width: 300px; height: 300px;"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="thisWeek">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsPostedThisWeek['count'] . '</h1><b>Posted This Week</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="thisMonth">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsPostedThisMonth['count'] . '</h1><b>Posted This Month</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="thisYear">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsPostedThisYear['count'] . '</h1><b>Posted This Year</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="allTime">
                <div>
                    <?php
                    echo
                    '<div class="alert alert-success">'
                    . '<h1>' . $statsPostedAllTime['count'] . '</h1><b>Posted All Time</b>'
                    . '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    

    <div class="tagProducts span3 pull-right last">
        <ul class="nav nav-list">
            <li>
            	<?php echo $this->Html->link('Manage Listings', array('plugin' => 'classifieds', 'controller' => 'classifieds', 'action' => 'index')); ?>
            	<?php echo $this->Html->link('Manage Categories', array('plugin' => 'classifieds', 'controller' => 'classifieds', 'action' => 'categories')); ?>
            </li>
        </ul>
    </div>

</div>

<?php
// set contextual search options
$this->set('forms_search', array(
    'url' => '/classifieds/classifieds/index/', 
	'inputs' => array(
		array(
			'name' => 'contains:title', 
			'options' => array(
				'label' => '', 
				'placeholder' => 'Classified Search',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
				)
			),
		)
	));
	
// set the contextual breadcrumb items
$this->set('context_crumbs', array('crumbs' => array(
	$this->Html->link(__('Admin Dashboard'), '/admin'),
	$page_title_for_layout,
)));

// set the contextual menu items
$this->set('context_menu', array('menus' => array(
    array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('Dashboard'), array('admin' => true, 'controller' => 'classifieds', 'action' => 'dashboard'), array('class' => 'active')),
			)
		),
        array(
            'heading' => 'Products',
            'items' => array(
                $this->Html->link(__('List Classifieds'), array('controller' => 'classifieds', 'action' => 'index')),
            )
        ),
        ))); ?>