<?php

namespace LukeZbihlyj\PhalconOrm\Annotations;

use Phalcon\Mvc\ModelInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

/**
 * @package LukeZbihlyj\PhalconOrm\Annotations\MetaDataInitializer
 */

class MetaDataInitializer {
    /**
     * Initializes the model's meta-data.
     *
     * @param Phalcon\Mvc\ModelInterface $model
     * @param Phalcon\DiInterface $di
     * @return array
     */

    public function getMetaData(ModelInterface $model, DiInterface $di) {
        $reflection = $di->get("annotations")->get($model);
        $properties = $reflection->getPropertiesAnnotations();

        if(!$properties) {
            throw new Exception("There are no properties defined on the model.");
        }

        $attributes = [];
        $notNull = [];
        $dataTypes = [];
        $dataTypesBind = [];
        $numericTypes = [];
        $primaryKeys = [];
        $nonPrimaryKeys = [];
        $identity = false;

        foreach($properties as $name => $collection) {
            if($collection->has('ORM\Column')) {
                $arguments = $collection->get('ORM\Column')->getArguments();

                if(isset($arguments['name'])) {
                    $columnName = $arguments['name'];
                } else {
                    $columnName = $name;
                }

                if(isset($arguments['type'])) {
                    switch($arguments['type']) {
                        case "integer":
                            $dataTypes[$columnName] = Column::TYPE_INTEGER;
                            $dataTypesBind[$columnName] = Column::BIND_PARAM_INT;
                            $numericTypes[$columnName] = true;
                            break;

                        case "string":
                            $dataTypes[$columnName] = Column::TYPE_VARCHAR;
                            $dataTypesBind[$columnName] = Column::BIND_PARAM_STR;
                            break;
                    }
                } else {
                    $dataTypes[$columnName] = Column::TYPE_VARCHAR;
                    $dataTypesBind[$columnName] = Column::BIND_PARAM_STR;
                }

                if(!$collection->has('ORM\Identity')) {
                    if(isset($arguments['nullable'])) {
                        if(!$arguments['nullable']) {
                            $notNull[] = $columnName;
                        }
                    }
                }

                $attributes[] = $columnName;

                if($collection->has('ORM\Primary')) {
                    $primaryKeys[] = $columnName;
                } else {
                    $nonPrimaryKeys[] = $columnName;
                }

                if($collection->has('ORM\Identity')) {
                    $identity = $columnName;
                }
            }
        }

        return [
            MetaData::MODELS_ATTRIBUTES => $attributes,
            MetaData::MODELS_PRIMARY_KEY => $primaryKeys,
            MetaData::MODELS_NON_PRIMARY_KEY => $nonPrimaryKeys,
            MetaData::MODELS_NOT_NULL => $notNull,
            MetaData::MODELS_DATA_TYPES => $dataTypes,
            MetaData::MODELS_DATA_TYPES_NUMERIC => $numericTypes,
            MetaData::MODELS_IDENTITY_COLUMN => $identity,
            MetaData::MODELS_DATA_TYPES_BIND => $dataTypesBind,
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => []
        ];
    }

    /**
     * Initializes the model's column mappings.
     *
     * @param Phalcon\Mvc\ModelInterface $model
     * @param Phalcon\DiInterface $di
     * @return array
     */

    public function getColumnMaps(ModelInterface $model, DiInterface $di) {
        $reflection = $di['annotations']->get($model);

        $columnMap = [];
        $reverseColumnMap = [];
        $renamed = false;

        foreach($reflection->getPropertiesAnnotations() as $name => $collection) {
            if($collection->has('ORM\Column')) {
                $arguments = $collection->get('ORM\Column')->getArguments();

                if(isset($arguments['name'])) {
                    $columnName = $arguments['name'];
                } else {
                    $columnName = $name;
                }

                $columnMap[$columnName] = $name;
                $reverseColumnMap[$name] = $columnName;

                if(!$renamed) {
                    if($columnName != $name) {
                        $renamed = true;
                    }
                }
            }
        }

        if($renamed) {
            return [
                MetaData::MODELS_COLUMN_MAP => $columnMap,
                MetaData::MODELS_REVERSE_COLUMN_MAP => $reverseColumnMap
            ];
        }

        return null;
    }
}
