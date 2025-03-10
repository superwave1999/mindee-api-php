---
title: US Bank Check OCR PHP
---
The PHP OCR SDK supports the [Bank Check API](https://platform.mindee.com/mindee/bank_check).

Using the [sample below](https://github.com/mindee/client-lib-test-data/blob/main/products/bank_check/default_sample.jpg), we are going to illustrate how to extract the data that we want using the OCR SDK.
![Bank Check sample](https://github.com/mindee/client-lib-test-data/blob/main/products/bank_check/default_sample.jpg?raw=true)

# Quick-Start
```php
<?php

use Mindee\Client;
use Mindee\Product\Us\BankCheck\BankCheckV1;

// Init a new client
$mindeeClient = new Client("my-api-key");

// Load a file from disk
$inputSource = $mindeeClient->sourceFromPath("/path/to/the/file.ext");

// Parse the file
$apiResponse = $mindeeClient->parse(BankCheckV1::class, $inputSource);

echo strval($apiResponse->document);
```

**Output (RST):**
```rst
########
Document
########
:Mindee ID: b9809586-57ae-4f84-a35d-a85b2be1f2a2
:Filename: default_sample.jpg

Inference
#########
:Product: mindee/bank_check v1.0
:Rotation applied: Yes

Prediction
==========
:Check Issue Date: 2022-03-29
:Amount: 15332.90
:Payees: JOHN DOE
         JANE DOE
:Routing Number:
:Account Number: 7789778136
:Check Number: 0003401

Page Predictions
================

Page 0
------
:Check Position: Polygon with 21 points.
:Signature Positions: Polygon with 6 points.
:Check Issue Date: 2022-03-29
:Amount: 15332.90
:Payees: JOHN DOE
         JANE DOE
:Routing Number:
:Account Number: 7789778136
:Check Number: 0003401

```

# Field Types
## Standard Fields
These fields are generic and used in several products.

### BasicField
Each prediction object contains a set of fields that inherit from the generic `BaseField` class.
A typical `BaseField` object will have the following attributes:

* **value** (`float|string`): corresponds to the field value. Can be `null` if no value was extracted.
* **confidence** (`float`): the confidence score of the field prediction.
* **boundingBox** (`[Point, Point, Point, Point]`): contains exactly 4 relative vertices (points) coordinates of a right rectangle containing the field in the document.
* **polygon** (`Point[]`): contains the relative vertices coordinates (`Point`) of a polygon containing the field in the image.
* **pageId** (`integer`): the ID of the page, is `null` when at document-level.
* **reconstructed** (`bool`): indicates whether an object was reconstructed (not extracted as the API gave it).

> **Note:** A `Point` simply refers to a List of two numbers (`[float, float]`).


Aside from the previous attributes, all basic fields have access to a custom `__toString` method that can be used to print their value as a string.


### AmountField
The amount field `AmountField` only has one constraint: its **value** is an optional `?float`.

### DateField
Aside from the basic `BaseField` attributes, the date field `DateField` also implements the following: 

* **dateObject** (`date`): an accessible representation of the value as a php object. Can be `null`.


### PositionField
The position field `PositionField` does not implement all the basic `BaseField` attributes, only **boundingBox**, **polygon** and **pageId**. On top of these, it has access to:

* **rectangle** (`[Point, Point, Point, Point]`): a Polygon with four points that may be oriented (even beyond canvas).
* **quadrangle** (`[Point, Point, Point, Point]`): a free polygon made up of four points.

### StringField
The text field `StringField` only has one constraint: its **value** is an optional `?string`.

## Page-Level Fields
Some fields are constrained to the page level, and so will not be retrievable to through the document.

# Attributes
The following fields are extracted for Bank Check V1:

## Account Number
**accountNumber** : The check payer's account number.

```php
echo $result->document->inference->prediction->accountNumber->value;
```

## Amount
**amount** : The amount of the check.

```php
echo $result->document->inference->prediction->amount->value;
```

## Check Number
**checkNumber** : The issuer's check number.

```php
echo $result->document->inference->prediction->checkNumber->value;
```

## Check Position
[📄](#page-level-fields "This field is only present on individual pages.")**checkPosition** : The position of the check on the document.

```php
foreach($result->document->checkPosition as $checkPositionElem){
    echo $checkPositionElem;
}->polygon->getCoordinates();
```

## Check Issue Date
**date** : The date the check was issued.

```php
echo $result->document->inference->prediction->date->value;
```

## Payees
**payees** : List of the check's payees (recipients).

```php
foreach ($result->document->inference->prediction->payees as $payeesElem)
{
    echo $payeesElem->value;
}
```

## Routing Number
**routingNumber** : The check issuer's routing number.

```php
echo $result->document->inference->prediction->routingNumber->value;
```

## Signature Positions
[📄](#page-level-fields "This field is only present on individual pages.")**signaturesPositions** : List of signature positions

```php
foreach ($result->document->inference->pages as $page)
{
    foreach ($page->prediction->signaturesPositions as $signaturesPositionsElem)
    {
        echo $signaturesPositionsElem->polygon;
        echo $signaturesPositionsElem->quadrangle;
        echo $signaturesPositionsElem->rectangle;
        echo $signaturesPositionsElem->boundingBox;
    }
}->polygon;
    echo $signaturesPositionsElem->quadrangle;
    echo $signaturesPositionsElem->rectangle;
    echo $signaturesPositionsElem->boundingBox;
}
```

# Questions?
[Join our Slack](https://join.slack.com/t/mindee-community/shared_invite/zt-1jv6nawjq-FDgFcF2T5CmMmRpl9LLptw)
