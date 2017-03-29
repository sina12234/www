<?php

class index_ajax
{

    public function pageAddArticleComment()
    {
        $uid     = isset($_POST['uid']) && (int)$_POST['uid'] ? (int)$_POST['uid'] : 0;
        $aid     = isset($_POST['aid']) && (int)$_POST['aid'] ? (int)$_POST['aid'] : 0;
        $comment = isset($_POST['content']) && trim($_POST['content']) ? trim($_POST['content']) : 0;

        $user = user_api::loginedUser();
        if (!isset($user['uid']) || !(int)($user['uid']) || $uid != $user['uid'])
            return interface_func::setMsg(1021);

        $data = [
            'userId'    => $uid,
            'articleId' => $aid,
            'comment'   => $comment
        ];

        $res = utility_services::call('blog/article/AddComment', $data);

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

        if (teacher_api::addFavTeacher($uid, $teacherId)) return interface_func::setmsg(0);

        return interface_func::setMsg(1);
    }

    public function pageCancelFav()
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

        $res = utility_services::call('/blog/article/CancelFav', $data);

        if ($res->code) return interface_func::setmsg(1);

        return interface_func::setMsg(0);
    }

    public function pageArticleImgUpload($inPath)
    {
        $editor = $_POST['editor'];

        $tid = isset($inPath[3]) && (int)($inPath[3]) ? (int)($inPath[3]) : 0;
        $uid = user_api::getLoginUid();

        if (!$uid || !$tid) return interface_func::setMsg(1000);
        if ($uid != $tid) return interface_func::setMsg(1021);

        if (empty($_FILES['userfile']['tmp_name'])) return interface_func::setMsg(1027);

        /*$thumbNail = new SThumbnail($_FILES['userfile']['tmp_name']);
        $thumbNail->setMaxSize(240, 135);
        $tmpFileName = utility_file::tempname('thumb');

        if ($thumbNail->genFile($tmpFileName) === false) return interface_func::setMsg(1028);*/

        $file = utility_file::instance();

        $res = $file->upload($_FILES['userfile']['tmp_name'], $uid, 'image', $_FILES['userfile']['name']);
        if (empty($res->fid)) return interface_func::setMsg(1029);

        //unlink($tmpFileName);
        $src = utility_cdn::filecdn().'/'.$res->fid;

        echo '<script>';
        echo 'window.parent.'.$editor.'.insertContent("<img src=\''.$src.'\' />");';
        echo '</script>';
        exit;
    }

    public function pageTeacherImgUpload($inPath)
    {
        $tid = isset($inPath[3]) && (int)($inPath[3]) ? (int)($inPath[3]) : 0;
        $uid = user_api::getLoginUid();

        if (!$uid || !$tid) return interface_func::setMsg(1000);
        if ($uid != $tid) return interface_func::setMsg(1021);

        if (empty($_FILES['file']['tmp_name'])) return interface_func::setMsg(1027);

        $thumbNail = new SThumbnail($_FILES['file']['tmp_name']);
        $thumbNail->setMaxSize(820, 464);
        $fileName820 = utility_file::tempname('thumb_820_464');

        if ($thumbNail->genFile($fileName820) === false) return interface_func::setMsg(1028);

        $thumbNail->setMaxSize(220, 124);
        $fileName220 = utility_file::tempname('thumb_220_124');

        if ($thumbNail->genFile($fileName220) === false) return interface_func::setMsg(1028);

        $file = utility_file::instance();

        $res820    = $file->upload($fileName820, $uid, 'image', $_FILES['file']['name']);
        $res220    = $file->upload($fileName220, $uid, 'image', $_FILES['file']['name']);
        $resOrigin = $file->upload($_FILES['file']['tmp_name'], $uid, "image", $_FILES['file']['name']);

        if (!empty($res220->fid) && !empty($res820->fid)) {
            unlink($fileName820);
            unlink($fileName220);

            $addImg = teacher_api::addTeacherImg(
                [
                    'teacherId' => $uid,
                    'imgName' => $_FILES['file']['name'],
                    'thumbMed' => $res220->fid,
                    'thumbBig' => $res820->fid,
                    'thumbOrigin' => $resOrigin->fid
                ]
            );

            if ($addImg) return interface_func::setMsg(0);

            return interface_func::setMsg(1029);
        }

        return interface_func::setMsg(1029);
    }

    public function pageUpdateImgPath()
    {
        $tid     = (int)($_POST['tid']);
        $imgPath = trim($_POST['src']);

        if (!$tid || !$imgPath) {
            return interface_func::setMsg(1000);
        }

        $uid = user_api::getLoginUid();
        if (!$uid || $uid != $tid) {
            return interface_func::setMsg(1021);
        }

        if (teacher_api::updateBannerImg($tid, $imgPath)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    public function pageUpdateTeacherImgName()
    {
        $tid     = (int)($_POST['tid']);
        $imgName = trim($_POST['name']);
        $imgId = (int)($_POST['imgId']);

        if (!$tid || !$imgName || !$imgId) {
            return interface_func::setMsg(1000);
        }

        $uid = user_api::getLoginUid();
        if (!$uid || $uid != $tid) {
            return interface_func::setMsg(1021);
        }

        if (teacher_api::updateTeacherImgName($imgId, $imgName)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    public function pagePublish()
    {
        $uid = user_api::getLoginUid();
        if (!$uid || $uid != (int)($_POST['tid'])) {
            return interface_func::setMsg(1021);
        }

        $status = 1;
        if (isset($_POST['button']) && $_POST['button'] == '存草稿') {
            $status = 0;
        }

        $content = trim($_POST['content']);
        if (!$content) return interface_func::setMsg(1000);

        $summary = $content;
        if (mb_strlen($content) > 200) {
            $summary = utility_tool::cut_str(strip_tags($content), 200);
        }

        if ((int)($_POST['tagId'])) {
            $tagId = (int)($_POST['tagId']);
        } elseif ($_POST['tagName']) {
            $tagId = teacher_api::addTag($uid, trim($_POST['tagName']));
            if (!$tagId) return interface_func::setMsg(1);
        } else {
            return interface_func::setMsg(1030);
        }

        $data = [
            'teacherId'  => (int)($_POST['tid']),
            'title'      => isset($_POST['title']) ? $_POST['title'] : '',
            'summary'    => $summary,
            'content'    => $content,
            'thumb'      => utility_tool::regularImgMatch($content),
            'tagId'      => $tagId,
            'type'       => isset($_POST['type']) ? (int)$_POST['type'] : 1,
            'status'     => $status,
            'top'        => isset($_POST['top']) && ($_POST['top'] == 'true') ? 1 : 0
        ];

        // 文章更新操作
        if (isset($_POST['button'], $_POST['articleId']) && ($_POST['button']) == '保存' && (int)$_POST['articleId']) {
            $articleInfo = teacher_api::getArticleDetail((int)$_POST['articleId']);
            $data['articleId'] = $articleInfo->pk_article;
            if (empty($articleInfo)) return interface_func::setMsg(1033);

            if ($tagId == $articleInfo->fk_tag) {
                $articleUpdateRes = utility_services::call('/blog/article/Update', $data);
                if ($articleUpdateRes->code) return interface_func::setMsg(1);

                return interface_func::setMsg(0);
            } else {
                $articleUpdateRes = utility_services::call('/blog/article/Update', $data);

                if ($articleUpdateRes->code == 0) {
                    // 更新tag id 先删除
                    $delMapTagArticleRes = teacher_api::delMapTagArticle($uid, $articleInfo->pk_article, $articleInfo->fk_tag);

                    if ($delMapTagArticleRes === false) {
                        $delInfo = [
                            'delArticleData' => $data,
                            'delArticleRes' => $articleUpdateRes,
                            'articleInfo' => $articleInfo,
                        ];
                        SLog::write('更新t_mapping_tag_article表错误,t_article表更新成功,info['.var_export($delInfo, 1).']');
                        return interface_func::setMsg(1035);
                    }

                    // 更新tag id 删除成功之后,在新增
                    $addMapTagArticleRes = teacher_api::addMapTagArticle(
                        [
                            'userId' => $uid,
                            'tagId' => $tagId,
                            'articleId' => $articleInfo->pk_article,
                            'status' => $status
                        ]
                    );

                    if ($addMapTagArticleRes === false) {
                        SLog::write('删除t_mapping_tag_article表成功之后,插入失败');
                        return interface_func::setMsg(1034);
                    }
                    return interface_func::setMsg(0);
                }
                return interface_func::setMsg(1);
            }
        } else { // 文章插入操作
            $res = utility_services::call('/blog/article/Add', $data);
            if ($res->code == 0 && !empty($res->result->articleId)) {
                // 同步tag mapping article 表
                $addMapTagArticleRes = teacher_api::addMapTagArticle(
                    [
                        'userId' => $uid,
                        'tagId' => $tagId,
                        'articleId' => $res->result->articleId,
                        'status' => $status
                    ]
                );

                // 记录插入错误日志
                if ($addMapTagArticleRes === false) {
                    $insertInfo = [
                        'insertArticleData' => $data,
                        'insertArticleRes' => $res,
                        'uid' => $uid,
                        'tagId' => $tagId
                    ];
                    SLog::write('插入t_mapping_tag_article表错误,t_article表插入成功,info['.var_export($insertInfo, 1).']');
                    return interface_func::setMsg(1036);
                }
                return interface_func::setMsg(0);
            }
            return interface_func::setMsg(1);
        }
    }

    public function pageDelArticle()
    {
        $articleId = (int)($_POST['dataId']);

        $uid = user_api::getLoginUid();
        if (!$uid || $uid != $_POST['tid']) {
            return interface_func::setMsg(1021);
        }

        $articleInfo = teacher_api::getArticleDetail($articleId);
        if (empty($articleInfo)) return interface_func::setMsg(1033);

        $data = ['articleId' => $articleId];
        $res = utility_services::call('/blog/article/DelArticle', $data);
        if ($res->code == 0) {
            $delMapTagArticleRes = teacher_api::delMapTagArticle($uid, $articleInfo->pk_article, $articleInfo->fk_tag);
            if ($delMapTagArticleRes) return interface_func::setMsg(0);
            SLog::write('删除t_mapping_tag_article表成功之后,插入失败');
        }

        return interface_func::setMsg(1);
    }

    public function pageDelImg()
    {
        $imgId = (int)($_POST['imgId']);

        $uid = user_api::getLoginUid();
        if (!$uid || $uid != $_POST['tid']) {
            return interface_func::setMsg(1021);
        }

        $data = ['imgId' => $imgId];
        $res = utility_services::call('/blog/teacher/DelImg', $data);

        if ($res->code) return interface_func::setMsg(1);

        return interface_func::setMsg(0);
    }

    public function pageUpdateCommentNum()
    {
        $articleId = (int)($_POST['articleId']);

        $uid = user_api::getLoginUid();
        if (!$uid || $uid != $_POST['tid']) {
            return interface_func::setMsg(1021);
        }

        $data = ['articleId' => $articleId];
        $res = utility_services::call('/blog/article/UpdateCommentNum', $data);

        if ($res->code) return interface_func::setMsg(1);

        return interface_func::setMsg(0);
    }
}

