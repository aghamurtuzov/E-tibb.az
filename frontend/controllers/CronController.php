<?php
namespace frontend\controllers;

use frontend\models\CronApiModel;

/**
 * Site controller
 */
class CronController extends MainController
{

    public $layout = false;
    /**
     * {@inheritdoc}
     */
    public function actions() {

    }

    /**
     * URL: https://e-tibb.az/cron/check-task
     * Desc: This function check tasks and insert all users
     */
    public function actionCheckTask() {
        $cronApiModel = new CronApiModel();

        $tasks = $cronApiModel->getTasks();
        if(!empty($tasks)) {
            foreach($tasks as $task) {
                if($task['case_id'] == 1) {
                    // Select and insert
                    $cronApiModel->fillUsers($task['case_id'], $task['send_type'], $task['assets']);
                    // Delete task at end
                    $cronApiModel->deleteTask($task['id']);
                }
            }
        }
    }

}