# Scalarizer [![Build Status](https://travis-ci.org/XAKEPEHOK/Scalarizer.svg?branch=master)]

`\XAKEPEHOK\Scalarizer\Scalarizer` - component, that can help you extract scalar value from value-objects. Scalarizer
can accept anytype input values and try return single scalar value

## Usage

Scalarizer constructor accept two arguments:
- associative array, where every key is a classname, and value is a callable, that can convert class to scalar value
- check class by reflection and try to return scalar value, if class has only one property

```php
<?php

$scalarizer = new \XAKEPEHOK\Scalarizer\Scalarizer(
    [DateTimeInterface::class => function (DateTimeInterface $dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }], 
    true //use reflection
);

echo $scalarizer->scalarize($someValue);
```

## Scalarisation methods priority

1) Check for already scalar or null values as input 
2) Scalarize by rules (array of callable)
3) Scalarize object by \JsonSerializable::jsonSerialize() (if implemented)
4) Scalarize object by Class::__toString() method (if implemented)
5) Scalarize by reflection. It return some value if class has only one property 