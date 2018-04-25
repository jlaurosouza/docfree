<?php

class SiteController extends Controller {

    public function actionIndex() {        
        $this->redirect(array("/login"));
    }
    
    public function actionError() {
        $this->redirect(array("index"));
    }
}