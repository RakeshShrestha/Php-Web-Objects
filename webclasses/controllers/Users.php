<?php

class Users extends Controller {

    private $umodel = null;

    public function __construct() {
        parent::__construct();
        $this->umodel = new Model('users');
    }

    public function index() {
        $view = $this->view;

        $view->title = 'Test';
        $view->heading = 'User List';
        $view->users = $this->umodel->select();

        $sess = $this->session;
        print_r($sess->test);

        $view->display();
    }

    public function jsonview_index() {
        $view = $this->view;
        
        $selschema = array();

        $view->title = 'Test';
        $view->heading = 'User List';
        $view->users = $this->umodel->select($selschema);

        echo json_encode($view->users);
    }

    public function create() {
        $view = $this->view;

        $view->title = 'Test';
        $view->heading = 'Create new user';

        $view->display();
    }

    public function save() {
        $req = $this->request;

        if ($req->isPost()) {
            $udata['fname'] = $req->getChar('fname');
            $udata['lname'] = $req->getChar('lname');
            $udata['email'] = $req->getChar('email');

            $id = $this->umodel->save($udata);
        }
        redirect('/users');
    }

    public function update() {
        $req = $this->request;

        if ($this->request->isPost()) {
            $udata['id'] = $req->getInt('id');
            $udata['fname'] = $req->getChar('fname');
            $udata['lname'] = $req->getChar('lname');
            $udata['email'] = $req->getChar('email');

            $this->umodel->save($udata);
        }
        redirect('/users');
    }

    public function delete($id = 0) {
        $id = (int) $id;
        if ($id > 0) {
            $this->umodel->delete($id);
        }
    }

}
