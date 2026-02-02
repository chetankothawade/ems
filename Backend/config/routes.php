<?php
declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;
use Slim\App;

use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\StudentExamController;

return function (App $app) {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    $app->group('/admin', function (RouteCollectorProxy $group) {

        $group->post('/exams', AdminExamController::class . ':create');
        $group->put('/exams/{id}', AdminExamController::class . ':update');
        $group->get('/exams/{id}/attempts', AdminExamController::class . ':attemptHistory');

    });


    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */
    $app->group('/student', function (RouteCollectorProxy $group) {

        $group->get('/exams', StudentExamController::class . ':dashboard');
        $group->get('/exams/{id}/attempts', StudentExamController::class . ':myAttempts');
        $group->post('/exams/{id}/start', StudentExamController::class . ':start');
        $group->post('/attempts/{id}/submit', StudentExamController::class . ':submit');

    });

};
