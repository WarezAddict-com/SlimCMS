<?php

namespace App\Source\ModelFieldBuilder;

class BuildFields
{
	protected $arFields;
	protected $arAllFields;

	protected $lastItemLink;

	protected $buildet = false;
	protected $defaultNoVisibleField = ['updated_at', 'created_at', 'id'];
	protected $defaultObject;

	public function __construct($obj=''){
		$default = new \stdClass();
		$default->name = 'default';
		$default->type = 'text';
		$default->className = 'form-control';

		$this->defaultObject = $default;
		
		if( $obj instanceof \stdClass )
			$this->setDefaultObject($obj);
	}

	public function add(\stdClass $item){
		$this->addField(FieldFactory::getField($item));
		
		return $this;
	}

	public function setFields(array $arFields){
		$this->arAllFields = $arFields;
		return $this;
	}

	public function addJsonShema($jsonShema=''){
		if( !is_array($jsonShema) )
			return;

		$neededObject = array_filter($jsonShema, function ($e) {return $e->name == "default";});
		$this->setDefaultObject(current($neededObject));

		while ($item = array_shift($jsonShema)) {
			if( is_object($item) && $item->name )
				$this->add($item);
		}

		return $this;
	}

	public function build(){
		if( !$this->arAllFields || $this->buildet )
			return $this;

		foreach( $this->arAllFields as $sort=>$field){
			if( isset($this->arFields[$field]) )
				continue;

			$obj = $this->makeDefault($field);
			$this->add($obj);
			if( in_array($this->lastItemNameAdd->name, $this->defaultNoVisibleField) )
				$this->lastItemNameAdd->noVisible();

		}

		if( !isset($this->arFields['default']) )
			$this->add($this->defaultObject);

		uasort($this->arFields, function($a, $b){if($a->sort == $b->sort) return strcmp($a->name, $b->name); return ($a->sort < $b->sort) ? -1 : 1;});

		$this->buildet = true;

		return $this;
	}

	public function addField(IField $field){
		$this->lastItemNameAdd = null;

		// move to this class method correct
		if( $field->correct() ){
			$this->arFields[$field->name] = $field;
			$this->lastItemNameAdd = $this->arFields[$field->name];
			$this->buildet = false;
		}

		return $this;
	}

	public function getAll(){
		return $this->build()->arFields;
	}

	public function getField($name){
		return $this->arFields[$name];
	}

	protected function makeDefault($name='default'){
		if( !$name )
			$name = 'default';
		$cloneObj = (object) (array) $this->defaultObject;
		$cloneObj->name = $name;
		return $cloneObj;
	}

	public function setDefaultObject(\stdClass $obj){
		$this->defaultObject = (object) array_merge((array) $this->defaultObject, (array) $obj);
	}
}