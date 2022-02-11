<?php

/**
 # Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
 # All rights reserved.
 #
 # Redistribution and use in source and binary forms, with or without
 # modification, are permitted provided that the following conditions are
 # met:
 #
 # Redistributions must retain the above copyright notice.
 */
final class cProfile extends cAuthController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {}

    public function api_search()
    {
        echo json_encode(array(
            'code' => 20000,
            'data' => ""
        ));
    }

    public function api_info()
    {
        echo json_encode(array(
            'code' => 20000,
            'data' => array(
                'userid' => $this->user->userid,
                'introduction' => $this->user->introduction,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
                'usertype' => $this->user->usertype,
                'reportunit' => $this->user->reportunit,
                'roles' => $this->user->roles
            )
        ));
    }
}
