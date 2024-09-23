<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;


function shuffle_assoc(&$array) {
    $keys = array_keys($array);
    shuffle($keys);
    $new_array = array();
    foreach ($keys as $key) {
        $new_array[$key] = $array[$key];
    }
    $array = $new_array;
    return true;
}


$docPath = 'ETOM_22-9_-1-2_ekzamen_OT.docx';
$phpWord = IOFactory::load($docPath, 'Word2007');


$fullText = '';
foreach ($phpWord->getSections() as $section) {
    foreach ($section->getElements() as $element) {
        if (method_exists($element, 'getText')) {
            $fullText .= $element->getText() . "\n";
        }
    }
}


$lines = explode("\n", $fullText);
$questions = [];
$currentQuestion = [];
foreach ($lines as $line) {
    if (trim($line) !== '') {
        if (preg_match('/^\d+\./', trim($line))) {
            if (!empty($currentQuestion)) {
                $questions[] = $currentQuestion;
            }
            $currentQuestion = ['question' => trim($line), 'answers' => []];
        } else {
            $currentQuestion['answers'][] = trim($line);
        }
    }
}
if (!empty($currentQuestion)) {
    $questions[] = $currentQuestion;
}


shuffle($questions);


$selected_questions = array_slice($questions, 0, 100);


foreach ($selected_questions as $index => $question) {
    echo ($index + 1) . ". " . $question['question'] . "\n";
    $answers = $question['answers'];
    shuffle_assoc($answers);
    foreach ($answers as $answer) {
        echo "   $answer\n";
    }
    echo "\n";
}
?>