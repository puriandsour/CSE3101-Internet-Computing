<?php
require_once __DIR__ . '/../models/Term.php';

class TermController {
    public function add($data) {
        return Term::add($data['term_number'], $data['school_year_id']);
    }
}
