 ### Execute this command in your terminal
 `composer require fjerbi/ultimate-admin-bundle`

### Add these lines in your services.yaml
```
 fjerbi\AdminBundle\Controller\AdminController:
        calls:
            - method: setContainer
              arguments: [ '@service_container' ]
```

### Add this in your routes.yaml
``` 
admin:
  resource: '@Admin/Controller/'
  type: annotation
  prefix: /admin 
  
  ```

### And finally execute this command
   ` php bin/console doctrine:schema:update --force `
   
 ##### check your database if the new tables were added successfully

#### NOTES: if you want to check the routes just execute this command
` php bin/console debug:router `
  
# What's included ?

