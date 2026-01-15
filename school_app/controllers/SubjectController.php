<?php
require_once __DIR__ . '/../models/Subject.php';

class SubjectController {

    public function add($data) {
        return Subject::create(
            $data['name'],
            $data['grade_id']
        );
    }
}
