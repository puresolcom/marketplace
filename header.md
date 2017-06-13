Hello, Developer!
=================

This is Awok.com Marketplace REST API documentation and Guide, Carefully read the the information below in order to get grasp of our API

----------


**Conventions**
-------------

In order to get the best result our of our API, you will have to read and understand the following conventions.


### Routing conventions

This is an entity-based Rest-API so most of our routes will be respecting the following convention with different HTTP methods *(GET, POST, PUT, DELETE)*

`/entity`

`/entity/:id`

`/entity/:id/sub-entity`

`/entity/:id/sub-entity/:id`   


**Important Note:** Some routes will be as follows `/entity/sub-entity` with no primary-key in between, this kind of routes are different that they have no data relations but for maintaining realistic patterns one good example route is `/product/attribute` instead of `/attribute` the reason for that is *"attribute"* alone is meaning-less without a product. 


### Resource customizing and filtering

This section is meant to explain how to

 1. Customize(Select) resource fields.
 2. Resolve relations.
 3. Filter results.
 4. Order results.
 5. Set results limit.
 

#### **Select Fields:**

You can select custom fields on most of our endpoints by appending the following query param

`?fields=field_one,field_two,field_three`


#### **Resolve relations:**

You can resolve entity relations and get related entities (Foreign keys) if there are any on most of our endpoints by appending the following query param

`?with=relationOne,relationTwo.relationThree`

#### **Filter results:**

You can filter results on most of our endpoints by appending the following query param

`?q=field_one:value,field_two<value,field_three=>value`

**Available Comparison operators**

 1. Equal Sign "=" or ":".
 2. Greater than ">".
 3. Less than "<".
 4. Greater than or equal ">="
 5. Less than or equal "<="
 6. Not Equal "!="

**Nested Relational Filters**

You can filter by relations by using the relations joiner (dot) symbol

`?q=relation.key<=500,relation.subRelation.key:value`

#### **Order Results:**

You can sort results on most of our endpoints by appending the following query param

`?sort=id,!age` 
Or
`?sort=id:asc,age:desc`

#### **Limit Results:**

You can set results limit on most of our endpoints by appending the following query param

`?limit=100` 
