# Invoice adjustment features for Laravel Nova

## Installation

You can install the package via composer:

```
composer require globalsprojects/invoice-adjustment
```

You will then need to publish the package's configuration and blade view files to your applications installation:

```
php artisan vendor:publish --provider="GlobalsProjects\InvoiceAdjustment\ToolServiceProvider"
```

Inside `App\Providers\NovaServiceProvider`update the tools function. This will include the link on the sidebar.  
```
    public function tools()
    {
        return [
        	[...]
        	new InvoiceAdjustment()
        ];
    }
```

## License

The Nova Invoice Adjustment is free software licensed under the MIT license.