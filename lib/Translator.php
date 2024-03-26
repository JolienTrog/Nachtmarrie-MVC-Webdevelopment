<?php

$authKey = "148cc420-c83e-4bc8-a4d0-4bd710d0d55a:fx"; // Replace with your key
$translator = new \DeepL\Translator($authKey);

$translatorResult = $translator->translateText('Hello, world!', 'nl', 'de');
echo $translatorResult->text; // Bonjour, le monde!


//function to translate text is translateText()