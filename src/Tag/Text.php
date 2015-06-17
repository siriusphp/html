<?php
namespace Sirius\Html\Tag;

class Text extends Input {

	protected $tag = 'input';

	protected $isSelfClosing = true;

	public function render() {
		$this->set( 'value', $this->getValue() );

		return parent::render();
	}
}
