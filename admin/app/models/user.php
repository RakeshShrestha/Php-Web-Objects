<?php

final class user extends model
{

    public function __construct($id = 0)
    {
        parent::__construct('users');

        if ($id)
            $this->select('*', 'id=?', $id);
    }

    public function insert()
    {
        $this->password = md5($this->password);
        $this->perms = 'user';
        $this->status = '2';
        $this->registerip = getRequestIP();
        $this->created = date('Y-m-d H:i:s');

        return parent::insert();
    }
}
