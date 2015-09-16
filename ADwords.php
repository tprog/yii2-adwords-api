<?php

namespace tprog\adwordsapi;

use yii\base\Component;


class ADwords extends Component
{
    public $userAgent = "INSERT_COMPANY_NAME_HERE";
    public $refresh_token = "INSERT_OAUTH2_REFRESH_TOKEN_HERE";
    public $developerToken = "INSERT_DEVELOPER_TOKEN_HERE";

    public $server_version = null;
}
