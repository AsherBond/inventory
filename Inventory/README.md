# Inventory module

The `Inventory` module module is part of the MSI (Multi-Source Inventory) project,
which replaces the legacy `CatalogInventory` module with new and expanded features and APIs for Inventory Management.  
 
The [Inventory Management overview](https://devdocs.magento.com/guides/v2.3/inventory/index.html)
describes the MSI project in more detail.

All Inventory Management modules follow the 
[Single Responsibility Principle](https://en.wikipedia.org/wiki/Single_responsibility_principle).
[Inventory management architecture](https://devdocs.magento.com/guides/v2.3/inventory/architecture.html) 
provides additional insight about the overall structure of these modules.

## Installation details

This module is installed as part of Magento Open Source. It cannot be deleted or disabled.

## Extension points and service contracts

All public interfaces related to this module are located in the `InventoryApi` module. 
Use the interfaces defined in `InventoryApi` to extend this module.
