<?php

final class cPages extends cAdminController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        exit();
    }

    public function dashboard_advancedforms() {
        $data['pagename'] = 'Advanced Forms';

        $this->res->display($data);
    }

    public function dashboard_simpletables() {
        $data['pagename'] = 'Simple Tables';

        $this->res->display($data);
    }

    public function manage_advancedforms() {
        $data['pagename'] = 'Advanced Forms';

        $this->res->display($data);
    }

    public function manage_simpletables() {
        $data['pagename'] = 'Simple Tables';

        $this->res->display($data);
    }

}
