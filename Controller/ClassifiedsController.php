<?php
App::uses('ClassifiedsAppController', 'Classifieds.Controller');
/**
 * Classifieds Controller
 *
 * @property Classified $Classified
 */
class ClassifiedsController extends ClassifiedsAppController {

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
			$this->set('categories', $this->Classified->Category->find('list', array('conditions' => array('model' => 'Classified'))));
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
		$this->set('classifieds', $this->paginate());
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
		$this->Classified->contain(array('Category', 'Creator'));
		$classified = $this->Classified->read();		//read is a short cut for find first
		$this->set('title_for_layout', $classified['Classified']['title'] . ' | ' . __SYSTEM_SITE_NAME);
		$this->set('classified', $classified);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
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
			$this->set('categories', $this->Classified->Category->find('threaded', array(
				'conditions' => array(
					'Category.model' => 'Classified',
				)
			)));
		}
	}
	
/**
 * post method
 * 
 */
	public function post() {
		return $this->add();
	}
 
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
			$this->request->data = $this->Classified->read(null, $id);
			if(CakePlugin::loaded('Categories')) {
				$this->set('categories', $this->Classified->Category->find('list', array('conditions' => array('model' => 'Classified'))));
			}
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
