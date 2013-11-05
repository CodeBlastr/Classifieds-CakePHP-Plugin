<?php
App::uses('ClassifiedsAppModel', 'Classifieds.Model');
/**
 * Classified Model
 *
 * @property Creator $Creator
 */
class Classified extends ClassifiedsAppModel {
		
	public $name = 'Classified';
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
		
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'Users.User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
/**
 * Constructor
 * 
 */
	public function __construct($id = false, $table = null, $ds = null) {
		if(CakePlugin::loaded('Media')) {
			$this->actsAs[] = 'Media.MediaAttachable';
		}
		
		if(CakePlugin::loaded('Categories')) {
			$this->actsAs[] = 'Categories.Categorizable';
			$this->hasAndBelongsToMany['Category'] = array(
				'className' => 'Categories.Category',
				'foreignKey' => 'foreign_key',
				'associationForeignKey' => 'category_id',
				'with' => 'Categories.Categorized'
			);
		}
		parent::__construct($id, $table, $ds);
	}

	
/**
 * Retrieves various stats for dashboard display
 * 
 * @todo This could probably be done in one query, then shaped with PHP ?
 * 
 * @param string $param
 * @return array|boolean
 */
	public function postedStats($period) {
        // configure period
        switch ($period) {
            case 'today':
                $rangeStart = date('Y-m-d', strtotime('today'));
                break;
            case 'thisWeek':
                $rangeStart = date('Y-m-d', strtotime('last sunday'));
                break;
            case 'thisMonth':
                $rangeStart = date('Y-m-d', strtotime('first day of this month'));
                break;
            case 'thisYear':
                $rangeStart = date('Y') . '-01-01';
                break;
            case 'allTime':
                $rangeStart = '0000-00-00';
                break;
            default:
                break;
		}
		$rangeStart .= ' 00:00:00';
        // calculate data
        $data = $this->find('all', array(
            'conditions' => array(
                'OR' => array(
                    "created >= '$rangeStart'",
                    "modified >= '$rangeStart'",
                    ),
                )
        ));
        $data['count'] = count($data);
        return ($data) ? $data : false;
    }

}
