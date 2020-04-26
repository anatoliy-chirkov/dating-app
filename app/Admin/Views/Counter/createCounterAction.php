<?php
/**
 * @var array $counterId
 * @var string $counterName
 */

$formTitle = 'Create counter action for ' . $counterName;
$formAction = '/counters/' . $counterId . '/actions/create';
require __DIR__ . '/' . 'counterActionForm.php';
