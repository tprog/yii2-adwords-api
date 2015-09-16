<?php

namespace tprog\adwordsapi;

use yii\base\Component;


class ADwords extends Component
{
    public $userAgent = "INSERT_COMPANY_NAME_HERE";
    public $refresh_token = "INSERT_OAUTH2_REFRESH_TOKEN_HERE";
    public $developerToken = "INSERT_DEVELOPER_TOKEN_HERE";

    public $server_version = null;


    private $_user = null;

    public function setClient($params){
        $this->_user = new \AdWordsUser();
        $this->_user->LogAll();
        $this->_user->SetClientCustomerId('889-324-3561');

        $this->_user->SetOAuth2Info($oauth2Info);
        $this->_user->SetUserAgent($this->userAgent);
        $this->_user->SetClientLibraryUserAgent($this->userAgent);
        $this->_user->SetClientCustomerId($clientCustomerId);
        $this->_user->SetDeveloperToken($this->developerToken);
    }
}
