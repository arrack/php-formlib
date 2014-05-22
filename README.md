FormLib
=============
generate html form object

Example
=============
```php
<?php
// input text box
echo FormLib::text('name', 'input your name');
echo FormLib::text('name', 'input your name', ['readonly' => true, 'size' => 5]);

// select
$options = [
    'A1' => 'AAA',
    'B1' => 'BBB'
];
echo FormLib::select('category', 'B1', $options);
```