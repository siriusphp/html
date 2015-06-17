<?php
namespace Sirius\Html\Tag;

class File extends Input {

	public function render() {
		$this->set( 'type', 'file' );

		return parent::render();
	}
}
