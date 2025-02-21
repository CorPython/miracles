<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
require_once("libs/Utils.php");
require_once("libs/Contents.php");
require_once("libs/Options.php");

//指定时区
date_default_timezone_set("Asia/Shanghai");

/**
 * 注册文章解析 hook
 * From AlanDecode(https://imalan.cn)
 */
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Contents','parseContent');
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Contents','parseContent');

/**
 * 主题启用时执行的方法
 */
function themeInit() {
    Helper::options()->commentsAntiSpam = false;//关闭反垃圾
	Helper::options()->commentsHTMLTagAllowed = '<a href=""> <img src=""> <img src="" class=""> <code> <del>';
    Helper::options()->commentsMaxNestingLevels = '9999';//最大嵌套层数
    Helper::options()->commentsPageDisplay = 'first';//强制评论第一页
    Helper::options()->commentsOrder = 'DESC';//将最新的评论展示在前
}

/**
 * 文章与独立页自定义字段
 */
function themeFields(Typecho_Widget_Helper_Layout $layout) {
    $banner = new Typecho_Widget_Helper_Form_Element_Text('banner', NULL, NULL,_t('文章头图'), _t('输入一个图片 url，作为缩略图显示在文章列表，没有则不显示'));
    $layout->addItem($banner);
	$excerpt = new Typecho_Widget_Helper_Form_Element_Text('excerpt', NULL, NULL,_t('文章摘要'), _t('输入一段文本来自定义摘要，如果为空则自动提取文章前 130 字。'));
    $layout->addItem($excerpt);
	$meta = new Typecho_Widget_Helper_Form_Element_Text('meta', NULL, NULL,_t('元信息'), _t('页面/文章内页会显示该页面的评论数和创建日期（元信息），如果你不需要，可以写一段文字来代替这些内容。'));
    $layout->addItem($meta);
}

/**
 * 获取主题版本号
 */
function themeVersion() {
    $info = Typecho_Plugin::parseInfo(__DIR__ . '/index.php');
    return $info['version'];
}

/**
 *  统计文章浏览数
 *  from:http://docs.qqdie.com/
 */
function get_post_view($archive) {
    $cid    = $archive->cid;
    $db     = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        echo 0;
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
 $views = Typecho_Cookie::get('extend_contents_views');
        if(empty($views)){
            $views = array();
        }else{
            $views = explode(',', $views);
        }
if(!in_array($cid,$views)){
       $db->query($db->update('table.contents')->rows(array('views' => (int) $row['views'] + 1))->where('cid = ?', $cid));
array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie
        }
    }
    echo $row['views'];
}
/**
 * 自动生成引用
 */
function generate_require($files,$type,$cdn = NULL) {
    if ($cdn)
        $path = $cdn.themeVersion()."/";
    else $path = Helper::options()->themeUrl("","Miracles");
    foreach ($files as $file) {
        if ($type == "js")
            echo "<script src=".$path."assets/".$type."/".$file.".".$type."></script>";
        elseif ($type == "css")
            echo "<link rel=\"stylesheet\" href=".$path."assets/".$type."/".$file.".".$type." />";
    }
}