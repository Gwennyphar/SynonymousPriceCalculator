<?php

class Shopware_Controllers_Widgets_Detail extends Enlight_Controller_Action
{

    public function dataAction() {

        if(!$this->request->has('articleId') or !is_int($this->request->get('articleId'))) throw new \Shopware\Components\Api\Exception\ParameterMissingException;
        $articleId = $this->request->get('articleId');

        try {
            $article = Shopware()->Modules()->Articles()->sGetArticleById(
                $articleId
            );
        } catch (RuntimeException $e) {
            $article = null;
        }

        $this->View()->assign('sArticle',$article);

    }

}