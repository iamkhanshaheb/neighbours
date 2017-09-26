<?php
App::uses('AppModel', 'Model');
/**
 * Post Model
 *
 * @property Comment $Comment
 */
class Post extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

	public $validate = array(
		'photo' => array(
                    'uploadError' => array(
                        'rule' => 'uploadError',
                        'message' => 'The cover image upload failed.',
                        'allowEmpty' => TRUE,
                    ),
                    'mimeType' => array(
                        'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
                        'message' => 'Please only upload images (gif, png, jpg).',
                        'allowEmpty' => TRUE,
                    ),
                    'fileSize' => array(
                        'rule' => array('fileSize', '<=', '10MB'),
                        'message' => 'Cover image must be less than 1MB.',
                        'allowEmpty' => TRUE,
                    ),
                    'processCoverUpload' => array(
                        'rule' => 'processCoverUpload',
                        'message' => 'Unable to process cover image upload.',
                        'allowEmpty' => TRUE,
                    ),
                ),
		
	);

	public function processCoverUpload($check = array()) {
		if (!is_uploaded_file($check['photo']['tmp_name'])) {
			return FALSE;
		}
		if (!move_uploaded_file($check['photo']['tmp_name'], WWW_ROOT . 'img' . DS . 'uploads' . DS . $check['photo']['name'])) {
			return FALSE;
		}
		$this->data[$this->alias]['photo'] = 'uploads' . DS . $check['photo']['name'];
		return TRUE;
	}

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'post_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
