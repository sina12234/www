<?php

class exam_ajax
{
    public function pageAddQuestion()
    {
        $user = user_api::loginedUser();
        if (empty($user['uid']))
            return interface_func::setMsg(1021);

        $org = user_organization::subdomain();
        $userOwner = !empty($org) ? $org->userId : 0;

        // 选项
        $selectOption = (int)($_POST['selectOption']);
        if (in_array($selectOption, [1,2,3], true)) {
            return interface_func::setMsg(4001); // 错误的选项类型
        }

        $subjectId = (int)($_POST['subjectId']);
        $gradeId = (int)($_POST['gradeId']);
        $title = trim($_POST['descript']) ?: ''; // 文字题干
        $imgDescTitle = count($_POST['imgDescTitle']) > 0 ? implode(',', $_POST['imgDescTitle']) : ''; // 图片题干
        $analysis = trim($_POST['analysis']);
        $difficulty = (int)($_POST['difficulty']);
        $correctAnswers = implode(',', $_POST['correctAnswers']);

        $data = [
            'userOwner'    => $userOwner,
            'userId'       => $user['uid'],
            'selectOption' => $selectOption,
            'source'       => 1, // 1:web
            'subjectId'    => $subjectId,
            'gradeId'      => $gradeId,
            'descript'     => $title,
            'imgDesc'      => $imgDescTitle,
            'analysis'     => $analysis,
            'result'       => $correctAnswers,
            'mode'         => $difficulty,
            'status'       => 0,
            'create_time'  => date('Y-m-d H:i:s')
        ];

        $res = utility_services::call('/exam/question/Add', $data);

        if ($res->code == 0 && !empty($res->result->questionId)) {
            if (!empty($_POST['answers']) && count($_POST['answers']) > 0) {
                // 同步answer 表
                foreach ($_POST['answers'] as $v) {
                    $answersData = [
                        'questionId' => $res->result->questionId,
                        'desc' => $v['desc'],
                        'imgDesc' => $v['imgDesc'],
                        'correct' => $v['correct']
                    ];
                    $answersRes = utility_services::call('/exam/answer/Add', $answersData);
                    // 记录日志
                    if ($answersRes->code) {
                        SLog::fatal('Synchronous data failure,params[%s]', var_export($answersData, 1));
                    }
                }

                // 同步相关map tag 表

            }

        }

        if ($res->code) return interface_func::setmsg(1);

        return interface_func::setMsg(0);
    }

    public function pageAddFav()
    {
        $uid       = isset($_POST['uid']) && (int)$_POST['uid'] ? (int)$_POST['uid'] : 0;
        $teacherId = isset($_POST['tid']) && (int)$_POST['tid'] ? (int)$_POST['tid'] : 0;

        if ($uid == $teacherId) return interface_func::setMsg(1025);

        $user = user_api::loginedUser();
        if (!isset($user['uid']) || !(int)($user['uid']) || $uid != $user['uid'])
            return interface_func::setMsg(1021);

        $data = [
            'userId'    => $uid,
            'teacherId' => $teacherId
        ];

        $res = utility_services::call('/blog/article/AddFavTeacher', $data);

        if ($res->code) return interface_func::setmsg(1);

        return interface_func::setMsg(0);
    }

}

