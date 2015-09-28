Extension for use API ADWords
=============================
Extension for use API ADWords

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tprog/yii2-adwords-api "*"
```

or add

```
"tprog/yii2-adwords-api": "*"
```

to the require section of your `composer.json` file.


Configuration
-------------
```
    'components' => [
    ...
        'ADwords'   => [
            'class'            => 'tprog\adwordsapi\ADwords',
            'developerToken'   => '***************',
            'server_version'   => 'v201506',
            'userAgent'        => 'You Adwords API client',
            'clientCustomerId' => '***-***-****',
            'client'           => [
                'client_id'     => '***************',
                'client_secret' => '***************',
                'refresh_token'    => '***************',
            ],
        ],
    ...
```


Usage
-----

Example create new account  :

```php

        $ADwordsUser = Yii::$app->ADwords->user;

        // Get the service, which loads the required classes.
        $managedCustomerService =
            $ADwordsUser->GetService('ManagedCustomerService');

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


```