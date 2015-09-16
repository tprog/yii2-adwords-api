<?php

namespace tprog\adwordsapi;

use yii\base\Component;


class ADwords extends Component
{
    public $userAgent = "INSERT_COMPANY_NAME_HERE";
    public $refresh_token = "INSERT_OAUTH2_REFRESH_TOKEN_HERE";
    public $developerToken = "INSERT_DEVELOPER_TOKEN_HERE";
    public $clientCustomerId = "";

    public $server_version = null;


    private $_user = null;

    public function setClient($oauth2Info)
    {
        $this->_user = new \AdWordsUser();
//        $this->_user->LogAll();

        $this->_user->SetOAuth2Info($oauth2Info);
        $this->_user->SetUserAgent($this->userAgent);
        $this->_user->SetClientLibraryUserAgent($this->userAgent);
        $this->_user->SetClientCustomerId($this->clientCustomerId);
        $this->_user->SetDeveloperToken($this->developerToken);
    }

    public function getClient()
    {
        return $this->_user;
    }

    public function test(){
        try {

            $this->CreateAccountExample($this->_user);
        } catch (\Exception $e) {
            printf("An error has occurred: %s\n", $e->getMessage());
        }
    }

    protected function CreateAccountExample(\AdWordsUser $user)
    {
        // Get the service, which loads the required classes.
        $managedCustomerService =
            $user->GetService('ManagedCustomerService');

        // Create customer.
        $customer = new \ManagedCustomer();
        $customer->name = 'Account #' . uniqid();
        $customer->currencyCode = 'EUR';
        $customer->dateTimeZone = 'Europe/London';

        // Create operation.
        $operation = new \ManagedCustomerOperation();
        $operation->operator = 'ADD';
        $operation->operand = $customer;

        $operations = [$operation];

        // Make the mutate request.
        $result = $managedCustomerService->mutate($operations);

        // Display result.
        $customer = $result->value[0];
        printf("Account with customer ID '%s' was created.\n",
            $customer->customerId);
    }
}
