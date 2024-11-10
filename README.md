# PHP RESTAPI CertificateManager client by HostingBE

HostingBE's CertificateManager API has many options for retrieving certificate data and create or renew certificates via your CertificateManager account. More options are being added all the time. This API is using the modern namespacing and you can easily install with the command below.

**Installing this API** 

`composer require hostingbe/api-client-certificatemanager`

**Using the API for the first time** 
To use it you need an account with CertificateManager, [create it here](https://www.certificatemanager.net/create-account).

When you are logged in, click on API settings and enter the IP address where the API requests come from (whitelist)
After saving you will receive a username and password which you need when using this API.

To connect to the API you need at least the following lines

```
use HostingBE\APIClientCertificateManager\CertificateManager;
use HostingBE\APIClientCertificateManager\Logger\APILogger;

$logger = (new APILogger)->create('test-api-certificatemanager');
$api = new CertificateManager($logger);
$response = $api->login('username','password');
```

You now have an object with your JWT token in response.

Below are some commands that you can execute with this API.

**Get the status from the API server**

`$response = $api->common('GET','/status']);`

**Edit contact via API**

`$response = $api->common('POST','/contact/edit/7/QeTweis0Is9p2MimQF1HHts4emVrhEN1/',['firstname' => 'firstname','lastname' => 'lastname',etc ..]);` 

**API commands available**
+ ping
+ get status API
+ list contacts
+ add contact 
+ edit contact


**Documentation**

Read more about the possibilities of this API on the [documentation](https://api.certificatemanager.net/api/docs/) page of CertificateManager