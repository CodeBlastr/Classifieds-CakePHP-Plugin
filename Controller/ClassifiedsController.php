<?php
App::uses('ClassifiedsAppController', 'Classifieds.Controller');
/**
 * Classifieds Controller
 *
 * @property Classified $Classified
 */
class AppClassifiedsController extends ClassifiedsAppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Utils.Tree', 'Media.Media');
/**
 * Uses
 *
 * @var array
 */
	public $uses = 'Classifieds.Classified';
	
	public function __construct($request = null, $response = null) {
		if (CakePlugin::loaded('Ratings')) {
			$this->components[] = 'Ratings.Ratings';
			$this->helpers[] = 'Ratings.Rating';
		}	
		parent::__construct($request, $response);
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('title_for_layout', __('Classified Ads') . ' | ' . __SYSTEM_SITE_NAME);
		$this->Classified->recursive = 0;
		
		if(CakePlugin::loaded('Categories')) {
			$this->paginate['contain'][] = 'Category';
			$this->paginate['contain'][] = 'Creator';
			
			if(isset($this->request->query['categories'])) {
				$this->set('title_for_layout', $this->request->query['categories'] . ' < ' . __('Classifieds') . ' | ' . __SYSTEM_SITE_NAME);
				$categoriesParam = explode(';', rawurldecode($this->request->query['categories']));
				$this->set('selected_categories', json_encode($categoriesParam));
				$joins = array(
			           array('table'=>'categorized', 
			                 'alias' => 'Categorized',
			                 'type'=>'left',
			                 'conditions'=> array(
			                 	'Categorized.foreign_key = Classified.id'
			           )),
			           array('table'=>'categories', 
			                 'alias' => 'Category',
			                 'type'=>'left',
			                 'conditions'=> array(
			                 	'Category.id = Categorized.category_id'
					   ))
			         );
				$this->paginate['joins'] = $joins;
				$this->paginate['order']['Classified.is_featured'] = 'DESC';
				$this->paginate['conditions']['Category.name'] = $categoriesParam;
				$this->paginate['fields'] = array(
					'DISTINCT Classified.id',
					'Classified.title',
					'Classified.description',
					'Classified.condition',
					'Classified.payment_terms',
					'Classified.shipping_terms',
					'Classified.price',
					'Classified.city',
					'Classified.state',
					'Classified.zip',
					'Classified.weight',
					'Classified.is_featured',
					'Classified.created',
					'Classified.expire_date'
					);
			}
		}

		if(isset($this->request->query['q'])) {
			$this->set('title_for_layout', $this->request->query['q'] . ' < ' . __('Classifieds') . ' | ' . __SYSTEM_SITE_NAME);
			$categoriesParam = explode(';', rawurldecode($this->request->query['categories']));
			$this->paginate['conditions']['Category.name'] = $categoriesParam;
			$this->paginate['conditions']['OR'] = array(
				'Classified.title LIKE' => '%' . $this->request->query['q'] . '%',
				'Classified.description' => '%' . $this->request->query['q'] . '%'
			);
		}
		$this->set('classifieds', $classifieds = $this->paginate());
		
		$conditions = !empty($categoriesParam) ? array('Category.model' => 'Classified', 'Category.name' => $categoriesParam) : array('Category.model' => 'Classified', 'Category.parent_id' => null);
		$contain = !empty($categoriesParam) ? array('ChildCategory' => array('ChildCategory')) : array(); 
		$this->set('categories', $this->Classified->Category->find('all', array('conditions' => $conditions, 'contain' => $contain)));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Classified->id = $id;
		if (!$this->Classified->exists()) {
			throw new NotFoundException(__('Invalid classified'));
		}
		$this->Classified->contain(array('Category', 'Creator' => array('Gallery' => 'GalleryThumb')));
		$classified = $this->Classified->read();
		$this->set('title_for_layout', $classified['Classified']['title'] . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('classified', $classified);
		if (CakePlugin::loaded('Categories')) {
			$adCategories = Set::extract('/Category/id', $classified);
			$this->set('categories', $this->Classified->Category->find('all', array('conditions' => array('Category.model' => 'Classified', 'Category.id' => $adCategories))));
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		// i don't remember why we moved to the post function now, but we never seem to use the add function (probably will switch later)
		$this->redirect(array('action' => 'post'));
		return $this->post();
	}
	
/**
 * post method
 * 
 */
	public function post() {
		$this->set('title_for_layout', __('Post a Classified Ad') . ' | ' . __SYSTEM_SITE_NAME);
		if ($this->request->is('post')) {
			$this->Classified->create();
			if ($this->Classified->save($this->request->data)) {
				$this->Session->setFlash(__('The Classified has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Classified could not be saved. Please, try again.'));
			}
		}
		
		if (CakePlugin::loaded('Categories')) {
			// yes I know it's stupid to have both here this was a quick fix
			$this->set('categories', $categories = $this->Classified->Category->find('threaded', array(
				'conditions' => array(
					'Category.model' => 'Classified',
				)
			)));
			// quick fix part is here, and should be moved to a custom classifieds controller for beefyy
			// or some way for the form input to ask for a tree list or a threaded list would be nice too
			$this->set('categoryList', $categoryList = $this->Classified->Category->generateTreeList(
				array(
					'Category.model' => 'Classified',
				),
		          null,
		          null,
		          '---'
		        ));
		}
	}
	
	



/**
 * Generate tree list with data provided (sans find() method being used)
 * 
 * $results should be a find('all') data structure
 * Was not completed, but it's a good idea, and should be moved to  ZuhaInflector::generateTreeList()

	public function generateTreeListSansFind(Model $Model, $results = array(), $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) {
		$overrideRecursive = $recursive;
		//extract($this->settings[$Model->alias]);
		if ($overrideRecursive !== null) {
			$recursive = $overrideRecursive;
		}

		$fields = null;
		if (!$keyPath && !$valuePath && $Model->hasField($Model->displayField)) {
			$fields = array($Model->primaryKey, $Model->displayField, $left, $right);
		}

		if (!$keyPath) {
			$keyPath = '{n}.' . $Model->alias . '.' . $Model->primaryKey;
		}

		if (!$valuePath) {
			$valuePath = array('%s%s', '{n}.tree_prefix', '{n}.' . $Model->alias . '.' . $Model->displayField);

		} elseif (is_string($valuePath)) {
			$valuePath = array('%s%s', '{n}.tree_prefix', $valuePath);

		} else {
			array_unshift($valuePath, '%s' . $valuePath[0], '{n}.tree_prefix');
		}

		$order = $Model->escapeField($left) . " asc";
		$stack = array();

		foreach ($results as $i => $result) {
			$count = count($stack);
			while ($stack && ($stack[$count - 1] < $result[$Model->alias][$right])) {
				array_pop($stack);
				$count--;
			}
			$results[$i]['tree_prefix'] = str_repeat($spacer, $count);
			$stack[] = $result[$Model->alias][$right];
		}
		if (empty($results)) {
			return array();
		}
		return Hash::combine($results, $keyPath, $valuePath);
	}
	 */
	
	
	
 
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Classified->id = $id;
		if (!$this->Classified->exists()) {
			throw new NotFoundException(__('Invalid classified'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Classified->save($this->request->data)) {
				$this->Session->setFlash(__('The classified has been saved'));
				$this->redirect(array('action' => 'index'));			 
			} else {
				$this->Session->setFlash(__('The classified could not be saved. Please, try again.'));
			}
		} else {
			if (CakePlugin::loaded('Categories')) {
				$this->Classified->contain(array('Category'));
				// yes I know it's stupid to have both here this was a quick fix
				$this->set('categories', $categories = $this->Classified->Category->find('threaded', array(
					'conditions' => array(
						'Category.model' => 'Classified',
					)
				)));
				// quick fix part is here, and should be moved to a custom classifieds controller for beefyy
				// or some way for the form input to ask for a tree list or a threaded list would be nice too
				$this->set('categoryList', $categoryList = $this->Classified->Category->generateTreeList(
					array(
						'Category.model' => 'Classified',
					),
			          null,
			          null,
			          '---'
			        ));
			}
			$this->request->data = $this->Classified->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Classified->id = $id;
		if (!$this->Classified->exists()) {
			throw new NotFoundException(__('Invalid classified'));
		}
		if ($this->Classified->delete()) {
			$this->Session->setFlash(__('Classified deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Classified was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * @param string $id
 */
	public function compare(){		
		if(empty($this->request->query)){
			$this->Session->setFlash(__('Please select two records to compare.'));
			$this->redirect($this->referer());
		} 

		$this->request->data =  $this->Classified->find('all', array('limit' => 2,'conditions' => array('Classified.id' => $this->request->query)));
		
		/*****Older way of posting data from the compare form. Now using the get method in order to URL append the Classified ID`s****/
		/*if($this->request->is('post')){
			debug($this->request->data);
			$classifieds = $this->Classified->find('all', array('conditions' => array('Classified.id' => set::extract('/id', $this->request->data['Classified']))));
			$this->set(compact('classifieds'));
		} else {
			$this->Session->setFlash(__('Please select two records to compare.'));
			$this->redirect(array('action' => 'index'));
		}*/
	}
 	
 
 
/**
 * Dashboard method
 * 
 */
	public function dashboard(){
        // $this->set('counts', $counts = array_count_values(array_filter(Set::extract('/Transaction/status', $Transaction->find('all')))));
		$this->set('statsPostedToday', $this->Classified->postedStats('today'));
		$this->set('statsPostedThisWeek', $this->Classified->postedStats('thisWeek'));
		$this->set('statsPostedThisMonth', $this->Classified->postedStats('thisMonth'));
		$this->set('statsPostedThisYear', $this->Classified->postedStats('thisYear'));
		$this->set('statsPostedAllTime', $this->Classified->postedStats('allTime'));
		// $this->set('transactionStatuses', $Transaction->statuses());
		// $this->set('itemStatuses', $TransactionItem->statuses());
		
		$this->set('title_for_layout', __('Classifieds Dashboard'));
		$this->set('page_title_for_layout', __('Classifieds Dashboard'));
        $this->layout = 'default';
	}

/**
 * contact method
 *
 * @param string $id
 * @return void
 */
	public function contact($id = null) {
		$this->Classified->id = $id;
		if (!$this->Classified->exists()) {
			throw new NotFoundException(__('Invalid classified'));
		}
		if ($this->request->is('post') || $this->request->is('push')) {
			$classified = $this->Classified->find('first', array('conditions' => array('Classified.id' => $id), 'contain' => array('Creator')));
			$email = $classified['Creator']['email'];
			$subject = __('%s received response on %s', $classified['Classified']['title'], __SYSTEM_SITE_NAME);
			$message = __('<p>Sender : %s</p><p>%s</p>', $this->request->data['Classified']['your_email'], strip_tags($this->request->data['Classified']['your_message']));
			
			if (!empty($email)) {
				try {
					$this->__sendMail($email, $subject, $message); 
					$this->Session->setFlash('Message sent');
					unset($this->request->data);
				} catch (Exception $e) {
					if (Configure::read('debug') > 0) {
						$this->Session->setFlash($e->getMessage());
					} else {
						$this->Session->setFlash('Error, please try again later.');
					}
				}
			} else {
				$this->Session->setFlash('Creator is not accepting contacts via email.');
			}
		}
		$this->Classified->contain(array('Category','Creator' => array('Gallery' => 'GalleryThumbnail')));
		$this->set('classified', $this->Classified->read(null, $id));
	}

/**
 * Categories method
 * A page for editing product categories. 
 */
    public function categories($parentId = null) {
        if (!empty($this->request->data['Category'])) {
            if ($this->Classified->Category->save($this->request->data)) {
                $this->Session->setFlash(__('Category saved'));
            }
        }
		
		$conditions = !empty($parentId) ? array('Category.parent_id' => $parentId, 'Category.model' => 'Classified') : array('Category.model' => 'Classified');
        $categories = $this->Classified->Category->find('threaded', array('conditions' => $conditions, 'order' => array('name' => 'ASC')));
        $this->set('parentCategories', $this->Classified->Category->generateTreeList(null, null, null, '--'));
        $this->set(compact('categories'));
        $this->set('page_title_for_layout', __('Classified Categories & Options'));
		//$this->layout = 'default';
		return $categories; // used in element Categories/categories
    }
}


if (!isset($refuseInit)) {
	class ClassifiedsController extends AppClassifiedsController {
		
	}
}
