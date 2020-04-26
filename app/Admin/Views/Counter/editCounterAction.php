<?php
/**
 * @var array $counterId
 * @var array $counterAction
 * @var string $counterName
 */

$formTitle = 'Edit counter action for ' . $counterName;
$formAction = '/counters/' . $counterId . '/actions/' . $counterAction['id'];
require __DIR__ . '/' . 'counterActionForm.php';
