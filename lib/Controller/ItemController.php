<?php

namespace Nachtmerrie\Controller;

use DeepL\DeepLException;
use DeepL\Translator;
use Nachtmerrie\Database\Item;
use Nachtmerrie\Database\Sentence;
use Nachtmerrie\Delete;
use Nachtmerrie\Extract;
use Nachtmerrie\Insert;
use Nachtmerrie\Select;
use Nachtmerrie\View;
use Nachtmerrie\Edit;


class ItemController extends Controller
{
    /**
     * @return void shows the page with all items in a table
     */
    public function indexAction(): void
    {
        $select = (new Select($this->connection))
            ->columns(['id', 'nl', 'de'])
            ->from(new Item());

        $result = $select->fetchAll();

        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('index-layout.phtml')
            ->setTitle('Nachtmerrie')
            ->setData('WL', 'Woordenlijst')
            ->setData('result', $result)
            ->setStylesheet('index.css');

        echo $viewObject->render();
    }

    /**
     * @return void gets a random word via ID from the database and shows the Dutch word
     */
    public function frontCardAction(): void
    {

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $items = (new Select($this->connection))
                ->columns(['id'])
                ->from(new Item)
                ->fetchAll();

            $idLen = count($items);
//            $idLen = implode('', $idLen);
            echo $idLen;
            $id = rand(38, $idLen);
        }

        $select = (new Select($this->connection))
            ->columns(['sentence.nl as snl', 'item.nl as inl'])
            ->where("item.id=:id", [":id" => $id])
            ->from(new Item())
            ->innerJoin(new Sentence(), 'id', 'item_id');


        $result = $select->fetchAll();

        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('frontCard.phtml')
            ->setTitle('Nachtmerrie')
            ->setData('result', $result)
            ->setData('id', $id)
            ->setStylesheet('index.css');

        echo $viewObject->render();
    }

    /**
     * @return void gets the german translation to the Dutch word from frontCardAction
     */
    public function backCardAction(): void
    {

        $id = $_GET['id'];

        $select = (new Select($this->connection))
            ->columns(['sentence.de as sde', 'item.de as ide'])
            ->where("item.id=:id", [":id" => $id])
            ->from(new Item())
            ->innerJoin(new Sentence(), 'id', 'item_id');

        $result = $select->fetchAll();


        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('backCard.phtml')
            ->setTitle('Nachtmerrie')
            ->setData('result', $result)
            ->setData('id', $id)
            ->setStylesheet('index.css');

        echo $viewObject->render();
    }

    /**
     * it extracts sentences and one word from each sentence from a json file, passes the data via
     * deepl API to translation, inserts the dutch-german sentence and word to database
     * @throws DeepLException
     */
    public function newListAction()
    {
        $extractData = (new Extract());
        $data = $extractData->execute();

        //für reale projekte in externer datei
        $authKey = "148cc420-c83e-4bc8-a4d0-4bd710d0d55a:fx";
        $translator = new Translator($authKey);
//      testtool for connection to server with deepl-mock docker [TranslatorOptions::SERVER_URL => 'localhost:3000']
        foreach ($data as $word => $sentence) {
            (new Insert($this->connection))
                ->value(['de' => $word, 'nl' => $translator->translateText($word, 'de', 'nl')->text])
                ->insertInto(new Item())
                ->execute();
            $itemID = (new Select($this->connection))
                ->columns(['id'])
                ->from(new Item)
                ->orderBy('id', 'desc')
                ->fetchAll()[0]['id'];
            (new Insert($this->connection))
                ->insertInto(new Sentence())
                ->value(['item_id' => $itemID, 'de' => $sentence, 'nl' => $translator->translateText($sentence, 'de', 'nl')->text])
                ->execute();
        }

// check account usage, only 500 000 characters free per month
        $usage = $translator->getUsage();
        if (!$usage->anyLimitReached()) {
            header("Location: /item");
        }
        echo 'Translation limit exceeded. <br>';
        if ($usage->character) {
            echo 'Characters: ' . $usage->character->count . ' of ' . $usage->character->limit . '<br>';
        }
        if ($usage->document) {
            echo 'Documents: ' . $usage->document->count . ' of ' . $usage->document->limit . '<br>';
        }
    }

    /**
     * takes a value from DB and deletes it
     * @return void
     */
    public function deleteAction()
    {
        //button auslösung einlesen
        if (isset($_POST['delete'])) {
            $delId = $_POST['delId'];

            //delete klasse ausführen
            $deleteSentence = (new Delete($this->connection))
                ->deleteFrom(new Sentence())
                ->where("item_id=:item_id")
                ->value(['item_id' => $delId]);
            $deleteSentence->execute();

            $deleteItem = (new Delete($this->connection))
                ->deleteFrom(new Item())
                ->where("id=:id")
                ->value(['id' => $delId]);
            $deleteItem->execute();

        }   //redirect to index
        header("Location: /item");
    }

    public function newItemAction()
    {

        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('newItem.phtml')
            ->setTitle('nieuw Word')
            ->setData('test', 'Add new Item')
            ->setStylesheet('index.css');

        echo $viewObject->render();


    }

    public function insertAction()
    {

        $nl = $_POST['nl'];
        $nl_sentence = $_POST['nl_sentence'];
        $de = $_POST['de'];
        $de_sentence = $_POST['de_sentence'];

        $insertItem = (new Insert($this->connection))
            ->value([
                'nl' => $nl,
                'de' => $de
            ])
            ->insertInto(new Item());
        $insertItem->execute();
        $insertSentence = (new Insert($this->connection))
            ->value([
                'nl' => $nl_sentence,
                'de' => $de_sentence
            ])
            ->insertInto(new Sentence());
        $insertSentence->execute();

        //redirect to other page with url
        header("Location: /item");
    }

    public function detailsAction()
    {
/*        if (isset($_POST['detailsID'])) {
            $id = $_POST['detailsID'];
        } else {
            $id = $_GET['id'];
        }*/
        $id = $_POST['detailsID'] ?? $_GET['id'];


        $select = (new Select($this->connection))
            ->columns(['sentence.de as sde', 'item.de as ide', 'sentence.nl as snl', 'item.nl as inl'])
            ->where("item.id=:id", [":id" => $id])
            ->from(new Item())
            ->innerJoin(new Sentence(), 'id', 'item_id');

        $result = $select->fetchAll();


        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('details.phtml')
            ->setTitle('Nachtmerrie')
            ->setData('result', $result)
            ->setData('id', $id)
            ->setStylesheet('index.css');

        echo $viewObject->render();
    }

    public function editAction()
    {
        if (isset($_POST['edit-submit'])) {

            $id = $_POST['detailsID'];

            $inl = $_POST['inl'];
            $snl = $_POST['snl'];
            $ide = $_POST['ide'];
            $sde = $_POST['sde'];

            (new Edit($this->connection))
                ->table(new Item())
                ->where("id=:id", [":id" => $id])
                ->values([
                    'nl' => $inl,
                    'de' => $ide
                ])
                ->execute();

            (new Edit($this->connection))
                ->table(new Sentence())
                ->where("item_id=:item_id", [":item_id" => $id])
                ->values([
                    'nl' => $snl,
                    'de' => $sde
                ])
                ->execute();

            echo "Test: $id";
            header("Location: details?id=$id");
            return;

        }
        $id = $_POST['id'];

        $select = (new Select($this->connection))
            ->columns(['sentence.de as sde', 'item.de as ide', 'sentence.nl as snl', 'item.nl as inl'])
            ->where("item.id=:id", [":id" => $id])
            ->from(new Item())
            ->innerJoin(new Sentence(), 'id', 'item_id');

        $result = $select->fetchAll();

        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('edit.phtml')
            ->setTitle('Nachtmerrie')
            ->setData('result', $result)
            ->setData('id', $id)
            ->setStylesheet('index.css');

        echo $viewObject->render();





    }

}


