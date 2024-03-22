<?php

namespace Nachtmerrie\Controller;

use Nachtmerrie\Database\Item;
use Nachtmerrie\Delete;
use Nachtmerrie\Extract;
use Nachtmerrie\Insert;
use Nachtmerrie\Select;
use Nachtmerrie\View;



class ItemController extends Controller
{
    /**
     * @return void shows the page with all items in a table
     */
    public function indexAction() : void
    {
        $select = (new Select($this->connection))
            ->columns(['id', 'nl', 'de'])
            ->from(new Item());

        $result = $select->fetchAll();


        $viewObject = (new View())
            ->setOuterLayout('outer-layout.phtml')
            ->setInnerLayout('index-layout.phtml')
            ->setTitle('Nachtmerrie')
            ->setData('WL','Woordenlijst')
            ->setData('result', $result)
            ->setStylesheet('index.css');

        echo $viewObject->render();
    }

    /**
     * @return void gets a random word via ID from the database and shows the Dutch word
     */
    public function frontCardAction() :void
    {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        } else {
            $id = rand(1, 3);
        }

        $select = (new Select($this->connection))

            ->columns(['nl'])
            ->where("id=:id", [":id"=>$id])
            ->from(new Item());

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
    public function backCardAction() :void
    {

        $id = $_GET['id'];
        $select = (new Select($this->connection))
            ->columns(['de'])
            ->where("id=:id", [":id"=>$id])
            ->from(new Item());

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

    public function newListAction()
    {
        $extractData = (new Extract());
        $data = $extractData->execute();
        print_r($data);

        foreach ($data as $word){
        $newList = (new Insert($this->connection))
            ->value(['de' => $word])
            ->insertInto(new Item());
        $newList->execute();
        }
        header("Location: /item");

    }
    public function deleteAction()
    {
        //button auslösung einlesen
        if (isset($_POST['delete'])) {
            $delId = $_POST['delId'];

            //delete klasse ausführen
            $delete = (new Delete($this->connection))
                ->deleteFrom(new Item())
                ->where("id=:id")
                ->value(['id' => $delId]);

            //überprüfen ob gelöscht

            //ausführen
            $delete->execute();
        }   //redirect to index
        header("Location: /item");

    }



}


