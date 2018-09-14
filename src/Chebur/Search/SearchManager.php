<?php

namespace App\Chebur\Search;

use Chebur\SearchBundle\Search\BuilderInterface;
use Chebur\SearchBundle\Search\Manager;

class SearchManager extends Manager
{
    /**
     * @inheritDoc
     */
    public function createBuilderAdmin() : BuilderInterface
    {
        $builder = parent::createBuilder();

        $builder->setPageRange(5);
        $builder->setLimits([
            10  => 10,
            50  => 50,
            100 => 100,
        ]);
        $builder->setParamNameLimit('l');
        $builder->setParamNamePage('p');
        $builder->setParamNameSort('s');
        $builder->setParamNameOrder('o');

        return $builder;
    }

}
