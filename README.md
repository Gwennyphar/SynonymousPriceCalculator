# SynonymousPriceCalculator
The SynonymousPriceCalculator Plugin for Shopware is a showcase and responsible for replacing the default product price calculation of the Shopware system with an individual calculation using a decorator service.

The decorator manipulates the array of pricerules and recalculates the prices for the product based on this changed rules. Additionally the plugin consumes the *Shopware_Modules_Basket_UpdateArticle_FilterSqlDefault* and the *Shopware_Plugins_HttpCache_GetCacheIds* Event according to the description bellow.

The plugin comes with the new *Shopware 5.2 architecture* and will not work in version prior to 5.2!

## Seperate calculation for sBasket class ##
Due to the fact, that the sBasket is not refactored as service yet, it´necessary to extend the sBasket::sUpdateArticle method so that it also uses the PriceCalculation service instead of the default SQL based calculation. Therefore the plugin consumes the *Shopware_Modules_Basket_UpdateArticle_FilterSqlDefault* filter event and manipulates the passed SQL query, so that the prices from the decorated PriceCalculation are added.

## HTTP Caching optimization ##
Normally the key for the http cache consists of the article id and the customer group id. This means, that a page and the prices are hold in the cache for all customers belonging to the same customer group.

This is good for the default behaviour of the shop, but if you want to use a more complex price calucation model, you might have to extend the caching mechanism to your needs. As an example this plugin consumes the *Shopware_Plugins_HttpCache_GetCacheIds* event and adds the user id of the actual session to the http cache arrays. That means, that now each loggedin customer has it´s own set of cached pages and it´s possible to calculate seperate prices per customer. This is only for demonstration and might not be useful in a production system due to lack of performance. In a real environment you might add the id of a discount calculation table or something similar for example.

## Follow me #
Find more informations, news, tipps and tutorials on https://synonymous.rocks.