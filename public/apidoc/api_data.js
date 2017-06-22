define({ "api": [
  {
    "type": "get",
    "url": "/product/attribute",
    "title": "2. Attributes List",
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
    "url": "/product/attribute/:id",
    "title": "1. Get Attribute",
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
    "type": "POST",
    "url": "/attribute",
    "title": "3. Create attribute",
    "description": "<p>Create a new attribute</p>",
    "group": "Attribute",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "name",
            "description": "<p>Name translations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "slug",
            "description": "<p>Attribute Slug (Alpha Numeric Dash)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "type",
            "description": "<p>Attribute Type (string, text, select, checkbox) (String by default)</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "multiple",
            "description": "<p>Multiple values case of type (select,checkbox) (Only when type is in (select, checkbox), false by default)</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "options",
            "description": "<p>Attribute options (Only when multiple is true)</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "required",
            "description": "<p>Attribute Required (false by default)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "position",
            "description": "<p>Order in front-end</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n\"name\":\n     [\n         {\n             \"locale\": \"en\",\n             \"value\"    : \"Brand\"\n         },\n         {\n             \"locale\": \"ar\",\n             \"value\"    : \"الماركه\"\n         }\n     ],\n\"slug\"        :    \"brand\",\n\"type\"        :    \"string\",\n\"multiple\"    :    false,\n\"required\"    :    true,\n\"position\"    :    1\n}",
          "type": "json"
        },
        {
          "title": "Request-Example-With-Options:",
          "content": "{\n\"name\":\n     [\n         {\n             \"locale\": \"en\",\n             \"value\"    : \"Sizes\"\n         },\n         {\n             \"locale\": \"ar\",\n             \"value\"    : \"الأحجام\"\n         }\n     ],\n\"slug\": \"sizes\",\n\"type\": \"select\",\n\"multiple\": true,\n\"options\" :\n         {\n         \"sizes-small\":\n         {\n         \"name\":\n             [\n                 {\n                     \"locale\": \"en\",\n                     \"value\" : \"Small\"\n                 },\n                 {\n                     \"locale\": \"ar\",\n                     \"value\" : \"صغير\"\n                 }\n             ]\n         },\n     \"sizes-large\":\n         {\n             \"name\":\n                 [\n                     {\n                         \"locale\": \"en\",\n                         \"value\" : \"Large\"\n                     },\n                     {\n                         \"locale\": \"ar\",\n                         \"value\" : \"كبير\"\n                     }\n                 ]\n         }\n     },\n\"required\" : true,\n\"position\" : 5\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/AttributeController.php",
    "groupTitle": "Attribute",
    "name": "PostAttribute"
  },
  {
    "type": "PUT",
    "url": "/attribute/:id",
    "title": "3. Update attribute",
    "description": "<p>Create a new attribute</p>",
    "group": "Attribute",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "name",
            "description": "<p>Name translations</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "type",
            "description": "<p>Attribute Type (string, text, select, checkbox) (String by default)</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "multiple",
            "description": "<p>Multiple values case of type (select,checkbox) (Only when type is in (select, checkbox), false by default)</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "options",
            "description": "<p>Attribute options (Only when multiple is true)</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "required",
            "description": "<p>Attribute Required (false by default)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "position",
            "description": "<p>Order in front-end</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n\"name\":\n     [\n         {\n             \"locale\": \"en\",\n             \"value\"    : \"Brand\"\n         },\n         {\n             \"locale\": \"ar\",\n             \"value\"    : \"الماركه\"\n         }\n     ],\n\"type\"        :    \"string\",\n\"multiple\"    :    false,\n\"required\"    :    true,\n\"position\"    :    1\n}",
          "type": "json"
        },
        {
          "title": "Request-Example-With-Options:",
          "content": "{\n\"name\":\n     [\n         {\n             \"locale\": \"en\",\n             \"value\"    : \"Sizes\"\n         },\n         {\n             \"locale\": \"ar\",\n             \"value\"    : \"الأحجام\"\n         }\n     ],\n\"type\": \"select\",\n\"multiple\": true,\n\"options\" :\n         {\n         \"sizes-small\":\n         {\n         \"name\":\n             [\n                 {\n                     \"locale\": \"en\",\n                     \"value\" : \"Small\"\n                 },\n                 {\n                     \"locale\": \"ar\",\n                     \"value\" : \"صغير\"\n                 }\n             ]\n         },\n     \"sizes-large\":\n         {\n             \"name\":\n                 [\n                     {\n                         \"locale\": \"en\",\n                         \"value\" : \"Large\"\n                     },\n                     {\n                         \"locale\": \"ar\",\n                         \"value\" : \"كبير\"\n                     }\n                 ]\n         }\n     },\n\"required\" : true,\n\"position\" : 5\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/AttributeController.php",
    "groupTitle": "Attribute",
    "name": "PutAttributeId"
  },
  {
    "type": "post",
    "url": "/user/auth/login",
    "title": "1. Login",
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
    "title": "2. Register",
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
            "type": "Number",
            "optional": false,
            "field": "phone_primary",
            "description": "<p>Primary Phone number</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
    "type": "DELETE",
    "url": "/location/country/:id",
    "title": "5. Delete country",
    "description": "<p>Hard delete a country</p>",
    "group": "Country",
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/CountryController.php",
    "groupTitle": "Country",
    "name": "DeleteLocationCountryId"
  },
  {
    "type": "get",
    "url": "/location/country",
    "title": "2. Countries List",
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
    "title": "1. Get Country",
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
    "type": "POST",
    "url": "/location/country",
    "title": "3. Create Country",
    "description": "<p>Create a new currency</p>",
    "group": "Country",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the Country</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "slug",
            "description": "<p>Country code name</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example",
          "content": "{\n \"name\" : \"Saudi Arabia\",\n \"slug\" : \"SA\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/CountryController.php",
    "groupTitle": "Country",
    "name": "PostLocationCountry"
  },
  {
    "type": "PUT",
    "url": "/location/country/:id",
    "title": "4. Update country",
    "description": "<p>Update country information</p>",
    "group": "Country",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>Name of the country</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example",
          "content": "{\n \"name\" : \"Saudi Arabia\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/CountryController.php",
    "groupTitle": "Country",
    "name": "PutLocationCountryId"
  },
  {
    "type": "DELETE",
    "url": "/currency/:id",
    "title": "5. Delete currency",
    "description": "<p>Hard delete a currency</p>",
    "group": "Currency",
    "version": "0.0.0",
    "filename": "app/Modules/Currency/Controllers/CurrencyController.php",
    "groupTitle": "Currency",
    "name": "DeleteCurrencyId"
  },
  {
    "type": "get",
    "url": "/currency",
    "title": "2. Currencies List",
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
    "title": "1. Get Currency",
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
    "type": "POST",
    "url": "/currency",
    "title": "3. Create Currency",
    "description": "<p>Create a new currency</p>",
    "group": "Currency",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the currency</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "symbol",
            "description": "<p>Currency Symbol</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "conversion_factor",
            "description": "<p>Currency Conversion factor against base currency</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n   \"name\"                : \"Egyptian Pound\",\n   \"symbol\"              : \"EGP\",\n   \"conversion_factor\"   : \"0.22\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Currency/Controllers/CurrencyController.php",
    "groupTitle": "Currency",
    "name": "PostCurrency"
  },
  {
    "type": "PUT",
    "url": "/currency/:id",
    "title": "4. Update Currency",
    "description": "<p>Update currency information</p>",
    "group": "Currency",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>Name of the currency</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "symbol",
            "description": "<p>Currency Symbol</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "conversion_factor",
            "description": "<p>Currency Conversion factor against base currency</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n   \"name\"                : \"Egyptian Pound\",\n   \"symbol\"              : \"EGP\",\n   \"conversion_factor\"   : \"0.22\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Currency/Controllers/CurrencyController.php",
    "groupTitle": "Currency",
    "name": "PutCurrencyId"
  },
  {
    "type": "DELETE",
    "url": "/location/:id",
    "title": "5. Delete location",
    "description": "<p>Hard delete a location</p>",
    "group": "Location",
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/LocationController.php",
    "groupTitle": "Location",
    "name": "DeleteLocationId"
  },
  {
    "type": "get",
    "url": "/location",
    "title": "2. Locations List",
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
    "title": "1. Get Location",
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
    "type": "POST",
    "url": "/location/location",
    "title": "3. Create Location",
    "description": "<p>Create a new currency</p>",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the Location</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "slug",
            "description": "<p>Location code name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Location type (city, area ..)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "parent_id",
            "description": "<p>Parent Location ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "country_id",
            "description": "<p>Location Country ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"name\"          : \"Dubai\",\n \"slug\"          : \"ae-du\",\n \"type\"          : \"city\",\n \"parent_id\"     : 10,\n \"country_id\"    : 1\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/LocationController.php",
    "groupTitle": "Location",
    "name": "PostLocationLocation"
  },
  {
    "type": "PUT",
    "url": "/location/:id",
    "title": "4. Update location",
    "description": "<p>Update location information</p>",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>Name of the Location</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "parent_id",
            "description": "<p>Parent Location ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"name\"          : \"Dubai\",\n \"parent_id\"     : 10\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Location/Controllers/LocationController.php",
    "groupTitle": "Location",
    "name": "PutLocationId"
  },
  {
    "type": "DELETE",
    "url": "/product/:id",
    "title": "6. Delete product",
    "description": "<p>Soft delete a product</p>",
    "group": "Product",
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/ProductController.php",
    "groupTitle": "Product",
    "name": "DeleteProductId"
  },
  {
    "type": "get",
    "url": "/product",
    "title": "2. Products List",
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
    "title": "1. Get Product",
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
    "url": "/product/:id/attributes",
    "title": "5. Get Product Attributes",
    "description": "<p>Listing product attributes along with their values</p>",
    "group": "Product",
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/ProductController.php",
    "groupTitle": "Product",
    "name": "GetProductIdAttributes"
  },
  {
    "type": "POST",
    "url": "/product",
    "title": "3. Create product",
    "description": "<p>Create a new product</p>",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "title",
            "description": "<p>Title translations</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": false,
            "field": "description",
            "description": "<p>Description translations</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "stock",
            "description": "<p>Stock quantity</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "upc",
            "description": "<p>UPC code of the product</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "sku",
            "description": "<p>SKU code of the product</p>"
          },
          {
            "group": "Parameter",
            "type": "Decimal",
            "optional": false,
            "field": "price",
            "description": "<p>Price of the product</p>"
          },
          {
            "group": "Parameter",
            "type": "Decimal",
            "optional": false,
            "field": "discount_price",
            "description": "<p>Price to be deducted from origin price as discount</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "currency_id",
            "description": "<p>ID of the base currency for this product</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "store_id",
            "description": "<p>ID of the store to assign this product to</p>"
          },
          {
            "group": "Parameter",
            "type": "Number[]",
            "optional": true,
            "field": "categories",
            "description": "<p>Array of categories IDs for this product</p>"
          },
          {
            "group": "Parameter",
            "type": "Number[]",
            "optional": true,
            "field": "tags",
            "description": "<p>Array of tags IDs for this product</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "attributes",
            "description": "<p>Array of custom product attributes values and translations</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n\"title\"            :\n[\n   {\n       \"locale\": \"en\",\n       \"value\": \"Sample Product\"\n   },\n   {\n       \"locale\": \"ar\",\n           \"value\": \"منتج رمزى\"\n       }\n],\n\"description\"    :\n   [\n       {\n           \"locale\": \"en\",\n           \"value\": \"Sample Description\"\n       },\n       {\n           \"locale\": \"ar\",\n           \"value\": \"وصف رمزى\"\n       }\n   ],\n   \"stock\"            : 10,\n   \"upc\"              : \"123456789124\",\n   \"sku\"              : \"123456789124\",\n   \"price\"            : \"99.99\",\n   \"discount_price\"   : \"49.99\",\n   \"currency_id\"      : 5,\n   \"store_id\"         : 10,\n   \"categories\"       : [100,200],\n   \"tags\"             : [300,400],\n   \"attributes\"       : {\n   \"color\":\n           [\n               {\n                   \"locale\"   : \"en\",\n                   \"value\"    : \"red\"\n               },\n               {\n                   \"locale\"   : \"ar\",\n                   \"value\"    : \"احمر\"\n               }\n           ],\n       \"material\":\n           [\n               {\n                   \"locale\"   : \"en\",\n                   \"value\"    : \"Leather\"\n               }\n           ],\n       \"size\": [1,2]\n   }\n}",
          "type": "json"
        },
        {
          "title": "Value-Based-Attribute-Example:",
          "content": "\"attributes\"    :{\n                      \"color\":\n                      [\n                          {\n                              \"locale\": \"en\",\n                              \"value\" : \"red\"\n                          },\n                          {\n                              \"locale\": \"ar\",\n                              \"value\"    : \"احمر\"\n                          }\n                      ]\n                  }",
          "type": "json"
        },
        {
          "title": "Option-Based-Attribute-Example:",
          "content": "\"attributes\"    :{\n                      \"size\": [1,2]\n                  }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/ProductController.php",
    "groupTitle": "Product",
    "name": "PostProduct"
  },
  {
    "type": "PUT",
    "url": "/product/:id",
    "title": "4. Update product",
    "description": "<p>Update product information</p>",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "title",
            "description": "<p>Title translations</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "description",
            "description": "<p>Description translations</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "stock",
            "description": "<p>Stock quantity</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "upc",
            "description": "<p>UPC code of the product</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sku",
            "description": "<p>SKU code of the product</p>"
          },
          {
            "group": "Parameter",
            "type": "Decimal",
            "optional": true,
            "field": "price",
            "description": "<p>Price of the product</p>"
          },
          {
            "group": "Parameter",
            "type": "Decimal",
            "optional": true,
            "field": "discount_price",
            "description": "<p>Price to be deducted from origin price as discount</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "urrency_id",
            "description": "<p>ID of the base currency for this product</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "store_id",
            "description": "<p>ID of the store to assign this product to</p>"
          },
          {
            "group": "Parameter",
            "type": "Number[]",
            "optional": true,
            "field": "categories",
            "description": "<p>Array of categories IDs for this product</p>"
          },
          {
            "group": "Parameter",
            "type": "Number[]",
            "optional": true,
            "field": "tags",
            "description": "<p>Array of tags IDs for this product</p>"
          },
          {
            "group": "Parameter",
            "type": "Object[]",
            "optional": true,
            "field": "attributes",
            "description": "<p>Array of custom product attributes values and translations</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n\"title\"            :\n[\n   {\n       \"locale\": \"en\",\n       \"value\": \"Sample Product\"\n   },\n   {\n       \"locale\": \"ar\",\n           \"value\": \"منتج رمزى\"\n       }\n],\n\"description\"    :\n   [\n       {\n           \"locale\": \"en\",\n           \"value\": \"Sample Description\"\n       },\n       {\n           \"locale\": \"ar\",\n           \"value\": \"وصف رمزى\"\n       }\n   ],\n   \"stock\"            : 10,\n   \"upc\"              : \"123456789124\",\n   \"sku\"              : \"123456789124\",\n   \"price\"            : \"99.99\",\n   \"discount_price\"   : \"49.99\",\n   \"currency_id\"      : 5,\n   \"store_id\"         : 10,\n   \"categories\"       : [100,200],\n   \"tags\"             : [300,400],\n   \"attributes\"       : {\n   \"color\":\n           [\n               {\n                   \"locale\"   : \"en\",\n                   \"value\"    : \"red\"\n               },\n               {\n                   \"locale\"   : \"ar\",\n                   \"value\"    : \"احمر\"\n               }\n           ],\n       \"material\":\n           [\n               {\n                   \"locale\"   : \"en\",\n                   \"value\"    : \"Leather\"\n               }\n           ],\n       \"size\": [1,2]\n   }\n}",
          "type": "json"
        },
        {
          "title": "Value-Based-Attribute-Example:",
          "content": "\"attributes\"    :{\n                      \"color\":\n                      [\n                          {\n                              \"locale\": \"en\",\n                              \"value\" : \"red\"\n                          },\n                          {\n                              \"locale\": \"ar\",\n                              \"value\"    : \"احمر\"\n                          }\n                      ]\n                  }",
          "type": "json"
        },
        {
          "title": "Option-Based-Attribute-Example:",
          "content": "\"attributes\"    :{\n                      \"size\": [1,2]\n                  }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Product/Controllers/ProductController.php",
    "groupTitle": "Product",
    "name": "PutProductId"
  },
  {
    "type": "get",
    "url": "/user/roles",
    "title": "1. Roles List",
    "description": "<p>Getting paginated objects list</p>",
    "group": "Role",
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
    "filename": "app/Modules/User/Controllers/RoleController.php",
    "groupTitle": "Role",
    "name": "GetUserRoles"
  },
  {
    "type": "DELETE",
    "url": "/store/:id",
    "title": "5. Delete store",
    "description": "<p>Soft delete a store</p>",
    "group": "Store",
    "version": "0.0.0",
    "filename": "app/Modules/Store/Controllers/StoreController.php",
    "groupTitle": "Store",
    "name": "DeleteStoreId"
  },
  {
    "type": "get",
    "url": "/store",
    "title": "2. Stores List",
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
    "title": "1. Get Store",
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
    "type": "post",
    "url": "/store",
    "title": "3. Create Store",
    "group": "Store",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Store name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "slug",
            "description": "<p>Store Slug (Sub-domain)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "street_address_1",
            "description": "<p>Store Physical Address 1</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "street_address_2",
            "description": "<p>Store Physical Address 2</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "city_id",
            "description": "<p>Store City</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "country_id",
            "description": "<p>Country for the store (will be detected automatically from the</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "user_id",
            "description": "<p>Store owner user ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "postal_code",
            "description": "<p>Store Postal code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example",
          "content": "{\n     \"name\": \"Almaya store\",\n     \"slug\": \"almaya\",\n     \"street_address_1\": \"G floor, Lake point tower, Cluster N, JLT\",\n     \"street_address_2\": \"G floor, Lake point tower, Cluster Z, JLT\",\n     \"city_id\": 1,\n     \"country_id\": 1,\n     \"postal_code\": \"12345\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Store/Controllers/StoreController.php",
    "groupTitle": "Store",
    "name": "PostStore"
  },
  {
    "type": "PUT",
    "url": "/store",
    "title": "4. Update Store",
    "group": "Store",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>Store name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "street_address_1",
            "description": "<p>Store Physical Address 1</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "street_address_2",
            "description": "<p>Store Physical Address 2</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "city_id",
            "description": "<p>Store City</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "country_id",
            "description": "<p>Country for the store (will be detected automatically from the</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "postal_code",
            "description": "<p>Store Postal code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example",
          "content": "{\n     \"name\": \"Almaya store\",\n     \"street_address_1\": \"G floor, Lake point tower, Cluster N, JLT\",\n     \"street_address_2\": \"G floor, Lake point tower, Cluster Z, JLT\",\n     \"city_id\": 1,\n     \"country_id\": 1,\n     \"postal_code\": \"12345\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/Store/Controllers/StoreController.php",
    "groupTitle": "Store",
    "name": "PutStore"
  },
  {
    "type": "DELETE",
    "url": "/taxonomy/:id",
    "title": "5. Delete taxonomy term",
    "description": "<p>Delete taxonomy term</p>",
    "group": "Taxonomy",
    "version": "0.0.0",
    "filename": "app/Modules/Taxonomy/Controllers/TaxonomyController.php",
    "groupTitle": "Taxonomy",
    "name": "DeleteTaxonomyId"
  },
  {
    "type": "get",
    "url": "/taxonomy",
    "title": "2. Terms List",
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
    "title": "1. Get Term",
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
    "title": "3. Create term",
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
    "title": "4. Update term",
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
    "type": "DELETE",
    "url": "/user/:id",
    "title": "4. Delete user",
    "description": "<p>Soft delete a user</p>",
    "group": "User",
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/UserController.php",
    "groupTitle": "User",
    "name": "DeleteUserId"
  },
  {
    "type": "get",
    "url": "/user",
    "title": "2. Users List",
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
    "title": "1. Get User",
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
  },
  {
    "type": "get",
    "url": "/user/:id/roles",
    "title": "5. Get User Roles",
    "description": "<p>Get user roles</p>",
    "group": "User",
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/RoleController.php",
    "groupTitle": "User",
    "name": "GetUserIdRoles"
  },
  {
    "type": "PUT",
    "url": "/user/:id",
    "title": "3. Update user",
    "description": "<p>Update user information</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>Full name</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "phone_primary",
            "description": "<p>Primary phone number</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "phone_secondary",
            "description": "<p>Secondary phone number</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "password",
            "description": "<p>Account Password</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "active",
            "description": "<p>Activate account</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": true,
            "field": "approved",
            "description": "<p>Approve account</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"name\"              : \"Mohammed Anwar\",\n \"phone_primary\"     : \"1234567890\",\n \"phone_secondary\"   : \"1234567890\",\n \"password\"          : \"p@ssw0rd\",\n \"active\":           : true,\n \"approved\":         : true\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/UserController.php",
    "groupTitle": "User",
    "name": "PutUserId"
  },
  {
    "type": "put",
    "url": "/user/:id/roles",
    "title": "6. Update user roles",
    "description": "<p>update user roles</p>",
    "group": "User",
    "parameter": {
      "examples": [
        {
          "title": "Request-Example-Attach-Detach-Roles:",
          "content": "{\n     \"attach\": [10,15,35],\n     \"detach\": [5]\n}",
          "type": "json"
        },
        {
          "title": "Request-Example-Sync-Roles:",
          "content": "{\n     \"sync\": [10,15,35]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Modules/User/Controllers/RoleController.php",
    "groupTitle": "User",
    "name": "PutUserIdRoles"
  }
] });
