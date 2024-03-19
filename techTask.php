<?php

function techTask(string $initString)
{
    $onWord = false;
    $strLength = mb_strlen($initString);

    $startWordChr = 0;

    $upperCasePositions = [];

    $resultString = '';

    for ($i = 0; $i < $strLength; $i++) {
        $currChr = mb_substr($initString, $i, 1);
        $isAlpha = isAlpha($currChr);

        if ($isAlpha && isUpper($currChr)) {
            $upperCasePositions[] = $i;
        }

        //if word begins
        if ($isAlpha && !$onWord) {
            $onWord = true;

            $startWordChr = $i;
        }

        // if word ends
        if ((!$isAlpha || ($i === ($strLength - 1))) && $onWord) {
            $onWord = false;
            $endWordChr = $i - 1;
            // if word in end of file
            if ($isAlpha) {
                $endWordChr++;
            }

            $wordLength = $endWordChr - $startWordChr + 1;

            $word = mb_substr($initString, $startWordChr, $wordLength);
            $wordInLowerCase = mb_strtolower($word);

            $resultWord = '';

            for ($j = 0; $j < $wordLength; $j++) {
                //take chars from the end
                $chr = mb_substr($wordInLowerCase, $wordLength - $j - 1, 1);

                if (in_array($j + $startWordChr, $upperCasePositions, true)) {
                    $chr = mb_strtoupper($chr);
                }

                $resultWord .= $chr;
            }

            $upperCasePositions = [];

            $resultString .= $resultWord;
        }

        //if not in word and not alpha
        if (!$isAlpha) {
            $resultString .= $currChr;
        }
    }
    return $resultString;
}

function isAlpha(string $char)
{
    if (preg_match('/^\p{L}+$/u', $char)) {
        return true;
    }

    return false;
}

function isUpper(string $chr)
{
    return $chr === mb_strtoupper($chr);
}

function tests()
{

    $testsArray = [];

    $testsArray[] = assertEquals('can', 'nac');
    $testsArray[] = assertEquals(' can ', ' nac ');
    $testsArray[] = assertEquals('ca', 'ac');
    $testsArray[] = assertEquals('c', 'c');
    $testsArray[] = assertEquals('Ca', 'Ac');
    $testsArray[] = assertEquals('C', 'C');
    $testsArray[] = assertEquals('Can cAn  caN CAN', 'Nac nAc  naC NAC');
    $testsArray[] = assertEquals("can'can`can,can\"can", "nac'nac`nac,nac\"nac");
    $testsArray[] = assertEquals('РуССкИЙ', 'ЙиКСсУР');


    return $testsArray;
}


function assertEquals($value, $expectedValue): array
{
    $status = $expectedValue === techTask($value) ? 'OK' : 'FAIL';

    if ($status === 'OK') {
        return [
            'Status' => $status,
            'InitValue' => $value,
        ];
    }

    return [
        'Status' => $status,
        'InitValue' => $value,
        'ExpectedValue' => $expectedValue,
        'Value' => techTask($value)
    ];

}

print_r(tests());
