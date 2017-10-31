<?php
class GoogleConsumer extends AbstractConsumer {
    public function __construct() {
        parent::__construct(Configure::read('Google.clientID'), Configure::read('Google.clientSecret'));
    }
}
?>