# vsf
Very Simple Framework


This framework implements an MVC architecture
and provides ready to use responses mechanisms to 
the folowing contexts:

  - API 
    - POST parameters sent to http://api.domain.com/controller/action
    - JSON success and error responses 
  - Site 
    - parameters are received as http://www.domain.com/controller/action/param1/param
    - respond with MVC HTML views
  - AJAX
    - POST parameters sent to http://api.domain.com/controller/action
    - JSON success and error responses 
  - CLI
    - system respond to php -f index.php controller action param1 param2 ...
    - JSON success and error responses 

Context: API
http://api.domain.com/controller/action
{
      api_key : ah9d3e6...7c1b09da,
      version : 1.0
      key1 : value1,
      :
      :
      keyN : valueN,
      signature : lgjdkugfiyrwuoygjyfdifJhfxj75jhgfjf14jg
}

Signature is calculated from implode('.', array(value1,...,valueN))
using the client RSA private key

Context: CLI
php -f index.php controller action param1 param2 ...

Context: AJAX
http://www.domain.com/controller/action
{
       token : f710ab0257...34dfee8c1,
       key1 : value1,
       :
       :
       keyN : valueN
}

Token is stored as a cookie and is related to client information

Context: SITE
http://www.domain.com/controller/action/param1/param2...
