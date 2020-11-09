<?php
# Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
# Redistributions must retain the above copyright notice.

abstract class cController
{

    public $req = null;

    public $res = null;

    public function __construct()
    {
        $this->req = req();
        $this->res = res();
    }
}
