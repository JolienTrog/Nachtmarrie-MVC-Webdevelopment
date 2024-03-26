<?php

namespace Nachtmerrie;

class Extract
{
    /**
     * @var array extracted text from json
     */
    public $textlines;
    /**
     * @var array single words
     */
    public $words;
    /**
     * @param array $textlines
     * @return $this textlines selected from json and saved in array
     */
    protected function getContent(): void
    {
        $jsonFile = "../Files/HP64.json";
        $json = file_get_contents($jsonFile);
        $data = json_decode($json, true);

        $resultLines = [];
        foreach ($data['pages'] as $page) {
            $textlines = $page['txtRns'];
            if (is_array($textlines)) {
                foreach ($textlines as $txtRn) {
                    $text = $txtRn['text'];
                    $resultLines[] = $text;
                }
            }
        }
        $this->textlines = $resultLines;
    }
    /**
     * @return $this one word and a fitting sentence as key -> value pair
     * deletes all punctuation marks and empty arrays
     * checks that there are no name used (specified to Harry Potter Book I)
     * @var array $words with single words and sentences
     */
    protected function extractContent(): void
    {
        $jsonFile = "../Files/HPnames.json";
        $json = file_get_contents($jsonFile);
        $deleteNames= json_decode($json);
        $pattern = '/Seite\s+\d+\s+von\s+\d+/';

        foreach ($this->textlines as $line) {
            $line = preg_replace($pattern, '', $line);
            $oneLine = str_replace(["\n", "\r"], " ", $line);
            $oneLinePure = preg_replace("/[^a-zA-ZöäüßÖÄÜ\s]/", "", $oneLine);
            $words = explode(' ', $oneLinePure);
            $randIndex = rand(0, count($words) - 1);

            $randWord = $words[$randIndex];
            if (!in_array($randWord, $deleteNames)) {

                $this->words[$randWord] = $oneLine; // Zufälliges Wort als Schlüssel, Zeile als Wert
            }
        }
    }
    /**
     * @return void
     * @var array $uniquWords unique words
     */
    protected function delDoubleWords(): void
    {
        $this->words = array_unique($this->words);
             sort($this->words);

    }

    /**
     * @return void removes empty arrays
     */
    protected function cleanUpValue():void
    {
        foreach ($this->words as $key => $value) {
            if (empty($value)) {
                unset($this->words[$key]);
            }
        }
//        foreach ($this->textlines as $key => $value) {
//            if (empty($value)) {
//                unset($this->textlines[$key]);
//            }
//        }
        $this->words = array_filter($this->words);
//        $this->textlines = array_filter($this->textlines);
    }

    protected function selectVocabulary(): array
    {
        shuffle($this->words);
        return array_slice($this->words, 1, 101);
    }

    public function execute(): array
    {
        $this->getContent();
        $this->extractContent();
        $this->cleanUPValue();
        return $this->words;
    }

}
//$data = (new Extract())->execute();
//print_r($data);
//$data = (new Extract());
//$data->getContent();
//$data->extractContent();
////$data->delDoubleWords();
//$data->cleanUpValue();
////print_r($data);
//var_dump($data->words);
//var_dump($data);