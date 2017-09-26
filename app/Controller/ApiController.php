<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class ApiController extends AppController {

    var $uses = array('Post','Comment');
    public $components = array('Paginator');
    
	public function add_post(){
        $this->autoRender = false;
        $this->response->type('json');
        $formData = $this->request->data;
        $data['text'] = $formData['text'];
        $data['lat'] = $formData['lat'];
        $data['lng'] = $formData['lng'];
        $data['user_id'] = $formData['user_id'];
        $data['user_name'] = $formData['user_name'];
        $data['user_photo_url'] = $formData['user_photo_url'];
        $data['photo'] = $this->request->form['photo'];

        if ($this->request->is('post')) {
            $this->Post->create();
            if (!$data['photo']['name']) {
                unset($data['photo']);
            }else{
                $data['photo']['name'] = $this->stringToSlug($data['photo']['name']);
            }
			if ($this->Post->save($data)) {
                    $this->response->statusCode(202);
                    $result['status'] ='success';
                    $json = json_encode($result);
                    $this->response->body($json);
                } else {
                    $this->response->statusCode(400);
                    $result['status'] ='failed';
                    $json = json_encode($result);
                    $this->response->body($json);
                }
		}
	}

    /**
    * all songs api
    *
    * @return void
    */
	public function get_posts() {
        $cart = array();
		$this->autoRender = false;
        $this->response->type('json');
        $data = $this->Post->find('all');
        $json = json_encode($data);
        header('Access-Control-Allow-Origin: *');
        $this->response->body($json);
	}

    /**
    * all user detail api
    *
    * @return void
    */
    public function get_post($id=null) {
        $this->autoRender = false;
        $this->response->type('json');
        if (!$this->Post->exists($id)) {
                      throw new NotFoundException(__('Invalid user'));
        }

        $options['conditions'] = array(
            'Post.id' => $id
        );
        $options['recursive'] = -1;
        $detail  = $this->Post->find('first', $options);
        $json = json_encode($detail);
        header('Access-Control-Allow-Origin: *');
        $this->response->body($json);
}

    /**
    * all categories api
    *
    * @return void
    */
    public function get_comments(){
        $this->autoRender = false;
        $this->response->type('json');
        $detail  = $this->Comment->find('all');
        $detail = Set::extract('/Comment/.', $detail);
        $json = json_encode($detail);
        header('Access-Control-Allow-Origin: *');
        $this->response->body($json);
    }
}
