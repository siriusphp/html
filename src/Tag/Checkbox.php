<?php
namespace Sirius\Html\Tag;

class Checkbox extends Input {

	public function render() {
		$checked = null;
		if ( is_array( $this->getValue() ) && in_array( $this->get( 'value' ), $this->getValue() ) ) {
			$checked = 'checked';
		} elseif ( $this->get( 'value' ) == $this->getValue() ) {
			$checked = 'checked';
		}
		$this->set( 'checked', $checked );
		$this->set( 'type', 'checkbox' );

		return parent::render();
	}
}
