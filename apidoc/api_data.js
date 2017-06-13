define({ "api": [
  {
    "type": "get",
    "url": "/product/attribute",
    "title": "Attributes List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Attribute",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/AttributeController.php",
    "groupTitle": "Attribute",
    "name": "GetProductAttribute"
  },
  {
    "type": "get",
    "url": "/product/attribute:id",
    "title": "Get Attribute",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Attribute",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/AttributeController.php",
    "groupTitle": "Attribute",
    "name": "GetProductAttributeId"
  },
  {
    "type": "post",
    "url": "/user/auth/login",
    "title": "Login",
    "description": "<p>Log a user into the  system and return OAuth 2 Tokens</p>",
    "group": "Authentication",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>Username/Email</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Password",
            "description": "<p>Password</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"username\" : \"awesomeuser@exmaple.com\",\n \"password\" : \"p@ssw0rd\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/AuthController.php",
    "groupTitle": "Authentication",
    "name": "PostUserAuthLogin"
  },
  {
    "type": "post",
    "url": "/user/auth/register",
    "title": "Register",
    "description": "<p>Registers a new user into the marketplace</p>",
    "group": "Authentication",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>First name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>E-mail Address</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone_primary",
            "description": "<p>Primary Phone number</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "phone_secondary",
            "description": "<p>Secondary Phone number</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>Password</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"name\" : \"Mohammed Anwar\",\n \"email\": \"mohammed@anwar.tld\",\n \"phone_primary\": \"1234567890\",\n \"phone_secondary\": \"1234567890\",\n \"password\" : \"p@ssw0rd\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/AuthController.php",
    "groupTitle": "Authentication",
    "name": "PostUserAuthRegister"
  },
  {
    "type": "get",
    "url": "/location/country",
    "title": "Countries List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Country",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/CountryController.php",
    "groupTitle": "Country",
    "name": "GetLocationCountry"
  },
  {
    "type": "get",
    "url": "/location/country/:id",
    "title": "Get Country",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Country",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/CountryController.php",
    "groupTitle": "Country",
    "name": "GetLocationCountryId"
  },
  {
    "type": "get",
    "url": "/currency",
    "title": "Currencies List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Currency",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Currency/Controllers/CurrencyController.php",
    "groupTitle": "Currency",
    "name": "GetCurrency"
  },
  {
    "type": "get",
    "url": "/currency/:id",
    "title": "Get Currency",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Currency",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Currency/Controllers/CurrencyController.php",
    "groupTitle": "Currency",
    "name": "GetCurrencyId"
  },
  {
    "type": "get",
    "url": "/location",
    "title": "Locations List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/LocationController.php",
    "groupTitle": "Location",
    "name": "GetLocation"
  },
  {
    "type": "get",
    "url": "/location/:id",
    "title": "Get Location",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/LocationController.php",
    "groupTitle": "Location",
    "name": "GetLocationId"
  },
  {
    "type": "get",
    "url": "/product",
    "title": "Products List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/ProductController.php",
    "groupTitle": "Product",
    "name": "GetProduct"
  },
  {
    "type": "get",
    "url": "/product/:id",
    "title": "Get Product",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/ProductController.php",
    "groupTitle": "Product",
    "name": "GetProductId"
  },
  {
    "type": "get",
    "url": "/store",
    "title": "Stores List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Store",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Store/Controllers/StoreController.php",
    "groupTitle": "Store",
    "name": "GetStore"
  },
  {
    "type": "get",
    "url": "/store/:id",
    "title": "Get Store",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Store",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Store/Controllers/StoreController.php",
    "groupTitle": "Store",
    "name": "GetStoreId"
  },
  {
    "type": "get",
    "url": "/taxonomy",
    "title": "Taxonomies List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Taxonomy",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Taxonomy/Controllers/TaxonomyController.php",
    "groupTitle": "Taxonomy",
    "name": "GetTaxonomy"
  },
  {
    "type": "get",
    "url": "/taxonomy/:id",
    "title": "Get Taxonomy",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "Taxonomy",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/Taxonomy/Controllers/TaxonomyController.php",
    "groupTitle": "Taxonomy",
    "name": "GetTaxonomyId"
  },
  {
    "type": "post",
    "url": "/taxonomy",
    "title": "Create term",
    "description": "<p>Create a new taxonomy term</p>",
    "group": "Taxonomy",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "name",
            "description": "<p>Taxonomy Term name (If string is used value will be inserted as default locale &quot;en&quot;)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Taxonomy Term Type</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "slug",
            "description": "<p>Taxonomy Slug for term (Required in case of non-string name)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "parent_id",
            "description": "<p>Taxonomy Term parent ID values)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n   \"type\": \"category\",\n   \"slug\": \"mobile-phones\",\n   \"parent_id\" : 100,\n   \"name\": [\n     {\n     \"locale\": \"en\",\n     \"name\"  : \"Mobile Phones\"\n     },\n     {\n     \"locale\": \"ar\",\n     \"name\"  : \"هواتف جوالة\"\n     }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Taxonomy/Controllers/TaxonomyController.php",
    "groupTitle": "Taxonomy",
    "name": "PostTaxonomy"
  },
  {
    "type": "put",
    "url": "/taxonomy",
    "title": "Update term",
    "description": "<p>Update a current taxonomy term</p>",
    "group": "Taxonomy",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "type",
            "description": "<p>Taxonomy Term Type</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "name",
            "description": "<p>Taxonomy Term name (If string is used value will be inserted as default locale &quot;en&quot;)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "slug",
            "description": "<p>Taxonomy Slug for term (Required in case of multilingual name</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "parent_id",
            "description": "<p>Taxonomy Term parent ID values)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n   \"type\": \"category\",\n   \"slug\": \"mobile-phones\",\n   \"parent_id\" : 100,\n   \"name\": [\n     {\n     \"locale\": \"en\",\n     \"name\"  : \"Mobile Phones\"\n     },\n     {\n     \"locale\": \"ar\",\n     \"name\"  : \"هواتف جوالة\"\n     }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Taxonomy/Controllers/TaxonomyController.php",
    "groupTitle": "Taxonomy",
    "name": "PutTaxonomy"
  },
  {
    "type": "get",
    "url": "/user",
    "title": "Users List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "q",
            "description": "<p>Comma-separated list of filters</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sort",
            "description": "<p>Comma-separated list of sorting rules</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Max number of results per response</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/UserController.php",
    "groupTitle": "User",
    "name": "GetUser"
  },
  {
    "type": "get",
    "url": "/user/:id",
    "title": "Get User",
    "description": "<p>Finds a specific object using the provided :id segment</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "fields",
            "description": "<p>Comma-separated list of required fields</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "with",
            "description": "<p>Comma-separated list of object relations</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/UserController.php",
    "groupTitle": "User",
    "name": "GetUserId"
  }
] });
