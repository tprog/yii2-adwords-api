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
        $this->_user->LogAll();

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

    public function test()
    {
        try {

            $this->LinkExistingAdwordsAccoount($this->_user);
        } catch (\Exception $e) {
            printf("An error has occurred: %s\n", $e->getMessage());
        }
    }

    protected function CreateAccount(\AdWordsUser $user)
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

    protected function ListAccounts(\AdWordsUser $user)
    {
        // Get the service, which loads the required classes.
        $managedCustomerService =
            $user->GetService('ManagedCustomerService');


        $customer = new \PendingInvitationSelector();
        $customer->managerCustomerId = $this->clientCustomerId;
        $customer->clientCustomerId = '533-434-5786';


        $managedCustomerService->getPendingInvitations($customer);

        // Create operation.
        $operation = new   \ManagedCustomerOperation();
        $operation->operator = 'ADD';
        $operation->operand = $customer;


        prn($managedCustomerService->mutateLink($operation));


        exit;

        // Create customer.
        $customer = new \PendingInvitationSelector();
        $customer->managerCustomerId = $this->clientCustomerId;
        $customer->clientCustomerId = '533-434-5786';

        // Create operation.
        $operation = new \LinkOperation();
        $operation->operator = 'PENDING';
        $operation->operand = $customer;

        $operations = [$operation];

        // Make the mutate request.
        $result = $managedCustomerService->mutate($operations);

        // Display result.
        $customer = $result->value[0];
        printf("Account with customer ID '%s' was created.\n",
            $customer->customerId);
    }


    public function GetAccounts()
    {
        // Get the service, which loads the required classes.
        $managedCustomerService =
            $this->_user->GetService('ManagedCustomerService');

        // Create selector.
        $selector = new \Selector();
        // Specify the fields to retrieve.
        $selector->fields = ['CustomerId', 'Name'];

        // Make the get request.
        $graph = $managedCustomerService->get($selector);
        $accounts = [];
        foreach ($graph->entries as $account) {
            if ($account->customerId > 0) {
                $accounts[$account->customerId] = $account->name;
            }
        }

        return $accounts;
    }


    function GetCampaigns() {
        // Get the service, which loads the required classes.
        $this->_user->SetClientCustomerId('763-834-9365');
        $campaignService = $this->_user->GetService('CampaignService');

        // Create selector.
        $selector = new \Selector();
        $selector->fields = array('Id', 'Name', 'Labels');
        // Labels filtering is performed by ID. You can use containsAny to select
        // campaigns with any of the label IDs, containsAll to select campaigns with
        // all of the label IDs, or containsNone to select campaigns with none of the
        // label IDs.
//        $selector->predicates[] = new \Predicate('Labels', 'CONTAINS_ANY',
//            array($labelId));

        $selector->ordering[] = new\ OrderBy('Name', 'ASCENDING');

        // Create paging controls.
        $selector->paging = new \Paging(0, \AdWordsConstants::RECOMMENDED_PAGE_SIZE);

        prn($campaignService->get($selector));
        exit;
        do {
            // Make the get request.
            $page = $campaignService->get($selector);

            // Display results.
            if (isset($page->entries)) {
                foreach ($page->entries as $campaign) {
                    printf("Campaign with name '%s' and ID '%d' and labels '%s'" .
                        " was found.\n", $campaign->name, $campaign->id,
                        implode(', ',
                            array_map(function($label) {
                                return sprintf('%d/%s', $label->id, $label->name);
                            }, $campaign->labels)));
                }
            } else {
                print "No campaigns were found.\n";
            }

            // Advance the paging index.
            $selector->paging->startIndex += \AdWordsConstants::RECOMMENDED_PAGE_SIZE;
        } while ($page->totalNumEntries > $selector->paging->startIndex);
    }


    function GetIp() {
        // Get the service, which loads the required classes.
        $this->_user->SetClientCustomerId('763-834-9365');
        $campaignCriterionService = $this->_user->GetService('CampaignCriterionService');



        $selector = new \Selector();
        $selector->fields = array('Id', 'CriteriaType', 'KeywordText');
        $selector->predicates[] = new \Predicate('CampaignId', 'EQUALS', '154329172');
        $selector->predicates[] = new \Predicate('CriteriaType', 'EQUALS', 'IP_BLOCK');
//        $selector->predicates[] = new Predicate('KeywordText', 'EQUALS', '192.168.0.1/32');
        $selector->paging = new \Paging(0, \AdWordsConstants::RECOMMENDED_PAGE_SIZE);
        $page = $campaignCriterionService->get($selector);

        prn($page);

        exit;


        // Create selector.
        $selector = new \Selector();
        $selector->fields = array('Id', 'Name', 'Labels');
        // Labels filtering is performed by ID. You can use containsAny to select
        // campaigns with any of the label IDs, containsAll to select campaigns with
        // all of the label IDs, or containsNone to select campaigns with none of the
        // label IDs.
//        $selector->predicates[] = new \Predicate('Labels', 'CONTAINS_ANY',
//            array($labelId));

        $selector->ordering[] = new\ OrderBy('Name', 'ASCENDING');

        // Create paging controls.
        $selector->paging = new \Paging(0, \AdWordsConstants::RECOMMENDED_PAGE_SIZE);

        prn($campaignService->get($selector));
        exit;
        do {
            // Make the get request.
            $page = $campaignService->get($selector);

            // Display results.
            if (isset($page->entries)) {
                foreach ($page->entries as $campaign) {
                    printf("Campaign with name '%s' and ID '%d' and labels '%s'" .
                        " was found.\n", $campaign->name, $campaign->id,
                        implode(', ',
                            array_map(function($label) {
                                return sprintf('%d/%s', $label->id, $label->name);
                            }, $campaign->labels)));
                }
            } else {
                print "No campaigns were found.\n";
            }

            // Advance the paging index.
            $selector->paging->startIndex += \AdWordsConstants::RECOMMENDED_PAGE_SIZE;
        } while ($page->totalNumEntries > $selector->paging->startIndex);
    }


    /**
     * @param $clientCustomerId
     * @param null $description
     * @return bool
     */
    public function LinkExistingAdwordsAccoount($clientCustomerId, $description = null)
    {

        $managedCustomerService = $this->_user->GetService('ManagedCustomerService');
        $customer = new \ManagedCustomerLink();
        $customer->managerCustomerId = preg_replace('#-#', '', $this->clientCustomerId);
        $customer->clientCustomerId = preg_replace('#-#', '', $clientCustomerId);
        $customer->pendingDescriptiveName = $description;
        $customer->linkStatus = "PENDING";

        // Create operation.
        $operation = new \LinkOperation();
        $operation->operator = 'ADD';
        $operation->operand = $customer;

        $operations = [$operation];

        // Make the mutate request.
        try {
            $result = $managedCustomerService->mutateLink($operations);
            return $result;
        } catch (\Exception $e) {
            return false;
        }

    }

    function GetLinkAccount(\AdWordsUser $user)
    {
        // Get the service, which loads the required classes.
        $managedCustomerService =
            $user->GetService('ManagedCustomerService');

        // Create selector.
        $selector = new \Selector();
        // Specify the fields to retrieve.
        $selector->fields = ['CustomerId', 'Name', 'Status'];

        // Make the get request.
        $graph = $managedCustomerService->get($selector);

        // Display serviced account graph.
        if (isset($graph->entries)) {
            // Create map from customerId to parent and child links.
            $childLinks = [];
            $parentLinks = [];
            if (isset($graph->links)) {
                foreach ($graph->links as $link) {
                    $childLinks[$link->managerCustomerId][] = $link;
                    $parentLinks[$link->clientCustomerId][] = $link;
                }
            }
            // Create map from customerID to account, and find root account.
            $accounts = [];
            $rootAccount = null;
            foreach ($graph->entries as $account) {
                $accounts[$account->customerId] = $account;
                if (!array_key_exists($account->customerId, $parentLinks)) {
                    $rootAccount = $account;
                }
            }
            // The root account may not be returned in the sandbox.
            if (!isset($rootAccount)) {
                $rootAccount = new \Account();
                $rootAccount->customerId = 0;
            }
            // Display account tree.
            print "(Customer Id, Account Name)\n";
            prn($rootAccount, null, $accounts, $childLinks, 0);
        } else {
            print "No serviced accounts were found.\n";
        }
    }
}
