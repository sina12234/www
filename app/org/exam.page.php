<?php

class org_exam extends STpl
{
    public function __construct()
    {
        $user = user_api::loginedUser();
        if (empty($user)) {
            $this->redirect("/user.main.login");
        }

        $org = user_organization::subdomain();

        $this->orgOwner = !empty($org) ? $org->userId : 0;
    }

    /**
     * 选题中心
     */
    public function pageQuestionCenter()
    {
        $this->render('/exam/org_teacher_questions_topic_center');
    }

    /**
     * 我的选题篮
     */
    public function pageMyQuestionCenter()
    {
        $this->render('/exam/org_teacher_questions_topic.html');
    }

    /**
     * 教师题库
     */
    public function pageQuestions()
    {
        $this->render('/exam/org_teacher_questions.html');
    }

    /**
     * 添加新题
     */
    public function pageAddQuestion()
    {
        $this->render('/exam/org_teacher_questions_add_title.html');
    }

    /**
     * 保存我的选题篮
     */
    public function pageUpdateQuestion()
    {
        $this->render('/exam/org_tq_my_topic_basket_update.html');
    }

    /**
     * 修改我的选题篮
     */
    public function pageChangeQuestion()
    {
        $this->render('/exam/org_tq_my_topic_basket_change.html');
    }

}
