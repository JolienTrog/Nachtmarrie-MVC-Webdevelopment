<?php

namespace Nachtmerrie\Controller;

use Nachtmerrie\Database\Item;
use Nachtmerrie\Select;
use Nachtmerrie\View;


class ItemController extends Controller
{
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

}

