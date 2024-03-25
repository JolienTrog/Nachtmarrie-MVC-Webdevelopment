<?php

namespace Nachtmerrie;

class Extract
{

    /**
     * @var array extracted text from json
     */
    protected $textlines;
    /**
     * @var array single words
     */

    protected $words;

    /**
     * @param array $textlines
     * @return $this textlines selected from json and saved in array
     */

    protected function getContent(): void
    {
        $jsonFile = "../Files/chatData1.json";
        $json = file_get_contents($jsonFile);
        $data = json_decode($json, true);

        $resultLines = [];
        foreach ($data['messages'] as $message) {
            $textlines = $message['text'];
            if (is_array($textlines)) {
                foreach ($textlines as $text) {
                    if (is_string($text)) {
                        $resultLines[] = $text;
                    }
                }
            }
            if (is_string($textlines)) {
                $resultLines[] = $textlines;
            }
        }
        $this->textlines = $resultLines;
    }

    /**
     * @return $this single words without punctuation characters
     * @var array $words with single words
     */
    protected function extractContent(): void
    {

        foreach ($this->textlines as $lines) {
            $randID = rand(0, strlen($lines));

            $oneLine = str_replace(["\n", "\r"], " ", $lines);
            $oneLinePure = preg_replace("/[^a-zA-ZöäüßÖÄÜ\s]/", "", $oneLine);
            foreach (explode(' ', $oneLinePure[$randID]) as $word) {
                $this->words[] = $word;
            }
        }
//        ---OLD
//        foreach ($this->textlines as $lines) {
//            $oneLine = str_replace(["\n", "\r"], " ", $lines);
//            $oneLinePure = preg_replace("/[^a-zA-ZöäüßÖÄÜ\s]/", "", $oneLine);
//            foreach (explode(' ', $oneLinePure) as $word) {
//                $this->words[] = $word;
//            }
//        }
    }

    //wörter abgleichen, wenn doppelt löschen

    /**
     * @return void
     * @var array $uniquWords unique words
     * @var array $innerArray
     * @var array $compArray all words from the document
     */
    protected function delDoubleWords(): void
    {
        $this->words = array_unique($this->words);
        if(!isset($this->words)){
            $this->words = $this->extractContent();
        }
        sort($this->words);

    }

    protected function cleanUpValue():void
    {
        $cleanUp = $this->words;
        //remove empty
        $this->words = array_filter($cleanUp);

        sort($this->words);

    }


    public function selectVocabulary(): array
    {
        shuffle($this->words);
        return array_slice($this->words, 1, 101);
    }

    public function execute()
    {
        $this->getContent();
        echo "test1";
        $this->extractContent();
        echo "test2";
        $this->delDoubleWords();
        echo "test3";
        $this->cleanUPValue();
        echo "test4";
        return $this->selectVocabulary();
    }
//
}
//$data = (new Extract())->execute();
//print_r($data);
