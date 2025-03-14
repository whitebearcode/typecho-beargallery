<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;


class BearGallery_Widget_Comments_Archive extends Widget_Abstract_Comments
{
     /**
     * 当前页
     *
     * @access private
     * @var integer
     */
    private $_currentPage;

    /**
     * 所有文章个数
     *
     * @access private
     * @var integer
     */
    private $_total = false;

    /**
     * 子父级评论关系
     *
     * @access private
     * @var array
     */
    private $_threadedComments = array();

    /**
     * 多级评论回调函数
     * 
     * @access private
     * @var mixed
     */
    private $_customThreadedCommentsCallback = false;

    /**
     * _singleCommentOptions  
     * 
     * @var mixed
     * @access private
     */
    private $_singleCommentOptions = NULL;


    private $_commentAuthors = array();

    /**
     * 安全组件
     */
    private $_security = NULL;

    /**
     * 构造函数,初始化组件
     *
     * @access public
     * @param mixed $request request对象
     * @param mixed $response response对象
     * @param mixed $params 参数列表
     * @return void
     */
    public function __construct($request, $response, $params = NULL)
    {
        
        parent::__construct($request, $response, $params);
        $this->parameter->setDefault('parentId=0&commentPage=0&commentsNum=0&allowComment=1');
        
        Typecho_Widget::widget('Widget_Security')->to($this->_security);

        /** 初始化回调函数 */
        if (function_exists('threadedComments')) {
            $this->_customThreadedCommentsCallback = true;
        }
    }
    
    public static function getOS($agent){
    $os = false;
    if (preg_match('/linux/i', $agent) && preg_match('/android 13/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 13';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 12/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 12';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 11/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 11';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 10/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 10';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android q/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android Q';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 9/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 9';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 7/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 7';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 6/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 6';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 5/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 5';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android 4/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android 4';
    }else if (preg_match('/linux/i', $agent) && preg_match('/android/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-android-fill"></i></span> Android';
    }else if (preg_match('/Mac/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-mac-fill"></i></span> MacOs';
    }else if (preg_match('/iphone/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-centos-fill"></i></span>Iphone';
    }else if (preg_match('/ipod/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-apple-fill"></i></span>Ipod';
    }else if (preg_match('/ipad/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-apple-fill"></i></span>Ipad';
    }else if (preg_match('/linux/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-apple-fill"></i></span> Linux';
    }
    else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 10 / 11';
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 8';
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 7';
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows Vista';
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows XP';
    }else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows ME';
    }else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 98';
    }else if (preg_match('/win/i', $agent) && strpos($agent, '95')){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 95';
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 2000';
    }else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows NT';
    }else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)){
        $os = '<span class="bear-icon __comment"><i class="ri-windows-fill"></i></span> Windows 32';
    }else{
        $os = '未知系统';
    }
echo $os;

    }
    
    public static function getClientBrowerInfo($agent){
    if (strpos($agent, 'MicroMessenger') !== false ){
        preg_match('/MicroMessenger\/([\d\.]+)/i',$agent,$weixn);
        $exp[0] = "<span class='bear-icon __comment'><i class='ri-wechat-fill'></i></span> 微信浏览器";
        $exp[1] = $weixn[1];
    }
    else if (stripos($agent, "Firefox/") > 0) {
        preg_match("/Firefox\/([^;)]+)+/i", $agent, $b);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-firefox-fill"></i></span> Firefox';
        $exp[1] = $b[1];
    } elseif (stripos($agent, "Maxthon") > 0) {
        preg_match("/Maxthon\/([\d\.]+)/", $agent, $aoyou);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-window-fill"></i></span> 傲游浏览器';
        $exp[1] = $aoyou[1];
    } elseif (stripos($agent, "QQ") > 0) {
        preg_match("/QQ\/([\d\.]+)/", $agent, $aoyou);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-qq-fill"></i></span> QQ浏览器';
        $exp[1] = $aoyou[1];
    } elseif (stripos($agent, "MSIE") > 0) {
        preg_match("/MSIE\s+([^;)]+)+/i", $agent, $ie);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-ie-fill"></i></span> IE浏览器';
        $exp[1] = $ie[1];
    } elseif (stripos($agent, "OPR") > 0) {
        preg_match("/OPR\/([\d\.]+)/", $agent, $opera);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-opera-fill"></i></span> Opera';
        $exp[1] = $opera[1];
    } elseif(stripos($agent, "Edg") > 0) {
        preg_match("/Edg\/([\d\.]+)/", $agent, $Edge);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-edge-new-fill"></i></span> Edge';
        $exp[1] = $Edge[1];
    } elseif (stripos($agent, "Chrome") > 0) {
        preg_match("/Chrome\/([\d\.]+)/", $agent, $google);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-chrome-fill"></i></span> Chrome';
        $exp[1] = $google[1];
    } elseif(stripos($agent,'rv:')>0 && stripos($agent,'Gecko')>0){
        preg_match("/rv:([\d\.]+)/", $agent, $IE);
        $exp[0] = '<span class="bear-icon __comment"><i class="ri-ie-fill"></i></span> IE浏览器';
        $exp[1] = $IE[1];
    } else {
        $exp[0] = "未知浏览器";
        $exp[1] = "";
    }
    return $exp[0].'('.$exp[1].')';
}


    public static function formatProvince(string $province) : string
    {
        if(empty($province)) {
            return '';
        }
 
        $checkArr = ["省","市","自治区","特别行政区"];
 
        for($i = 0; $i < count($checkArr); $i++) {
            if(strpos($province, $checkArr[$i]) === false) {
                continue;
            } else {
                $province = mb_strcut($province, 0, strrpos($province, $checkArr[$i]));
            }
        }
 
        return $province;
    }
     public static function GetAddress($ip){
         $IPlo = new IpLocation;
         if(empty($IPlo::getLocation($ip)['province'])){
             return $IPlo::getLocation($ip)['country'];
         }
         else{
             return self::formatProvince($IPlo::getLocation($ip)['province']);
         }
     }
     
 public static function getLocation($comments)
    {
        $db = \Typecho\Db::get();
        $prefix = $db->getPrefix();
        $coid = $comments->coid;
        
            if (!filter_var($comments->ip, FILTER_VALIDATE_IP)) {
                $location = "外星来客";
            }
            else{
           $location = self::GetAddress($comments->ip);
        }
        
        echo $location;
    }

    public static function updateLocation(int $coid, $location,$type)
    {
        $db = \Typecho\Db::get();
        $location = is_array($location) ? serialize($location) : $location;
        $result = $db->fetchAll($db->select('coid')->from('table.comments')->where('table.comments.coid = ?', $coid));
        if (is_array($result) && count($result) && $type== 'ipv6') {
            $updateQuery = $db->update('table.comments')->rows(array('ipv6_location' => $location))->where('coid = ?', $coid);
            return $db->query($updateQuery) > 0;
        }
         if (is_array($result) && count($result) && $type== 'ipv4') {

            $updateQuery = $db->update('table.comments')->rows(array('ipv4_location' => $location))->where('coid = ?', $coid);

            return $db->query($updateQuery) > 0;
        }
        return false;
    }
    
     public function commentRank($i){
          $db=\Typecho\Db::get();
    $mail=$db->fetchAll($db->select(array('COUNT(cid)'=>'all'))->from('table.comments')->where('mail = ?', $i->mail)->where('authorId = ?','0'));
    foreach ($mail as $sl){
    $all=$sl['all'];}
    if($i->authorId == $i->ownerId){
    echo '<span class="bear-tag bear-tag-red"><i class="ri-pencil-line"></i> 博主</span>';
    }
    else if($all<10 && $all>0) {
    echo '<span class="bear-tag bear-tag-blue"><i class="ri-user-3-line"></i> LV1</span>';
    }elseif ($all<20 && $all>=10) {
    echo '<span class="bear-tag bear-tag-blue"><i class="ri-user-3-line"></i> LV2</span>';
    }elseif ($all<40 && $all>=20) {
    echo '<span class="bear-tag bear-tag-blue"><i class="ri-user-3-line"></i> LV3</span>';
    }elseif ($all<80 && $all>=40) {
    echo '<span class="bear-tag bear-tag-blue"><i class="ri-user-3-line"></i> LV4</span>';
    }elseif ($all<100 && $all>=80) {
    echo '<span class="bear-tag bear-tag-blue"><i class="ri-user-3-line"></i> LV5</span>';
    }elseif ($all>=100) {
    echo '<span class="bear-tag bear-tag-blue"><i class="ri-user-3-line"></i> LV6</span>';
    }
     }
     
    
    /**
     * 评论回调函数
     * 
     * @access private
     * @return void
     */
    private function threadedCommentsCallback()
    {

        $singleCommentOptions = $this->_singleCommentOptions;
        if (function_exists('threadedComments')) {
            return threadedComments($this, $singleCommentOptions);
        }

        
?>
<div id="<?php $this->theId(); ?>" class="bear-comment comment-body<?php
    if ($this->levels > 0) {
        echo ' comment-child';
        $this->levelsAlt(' comment-level-odd', ' comment-level-even');
    } else {
        echo ' comment-parent';
    }
    $this->alt(' comment-odd', ' comment-even');
    echo $commentClass;
?>">

    	<div class="bear-avatar bear-has-avatar">
			<!---->
			<img src="<?php echo General::getAvatar($this->mail); ?>"
			alt="" class="bear-avatar-img">
		</div>
		<div class="bear-main">
			<div class="bear-row">
				<div class="bear-meta">
					<strong class="bear-nick">
						<?php General::CommentAuthor($this); ?>
					</strong>
					<?php self::commentRank($this);?>
					<!---->
					<!---->
					<!---->
					<!---->
					<!--<small class="bear-time">-->
					<span class="bear-tag bear-tag-green">
					    <i class="ri-time-line"></i>
						<time>
							<?php $singleCommentOptions->beforeDate();
                echo General::publishTime($this->created);
                $singleCommentOptions->afterDate(); ?>
						</time>
						</span>
					<!--</small>-->
					<!---->
				</div>
				<div class="bear-action">
				
				
						<?php $this->reply(); ?>
					
				</div>
			</div>
			<div class="bear-content">
			    <?php echo $this->getParent(); ?>
				<!---->
				<span>
					<p>
						<?php echo $this->content; ?>
					</p>
				</span>
			</div>
			<div class="bear-extras">
			
				<div class="bear-extra">
					
					<span class="bear-extra-text">
						<?php self::getOS($this->agent);?>
					</span>
				</div>
				<div class="bear-extra">
					
					<span class="bear-extra-text">
						<?php echo self::getClientBrowerInfo($this->agent);?>
					</span>
				</div>
			</div>
    <?php if ($this->children) { ?>
    <div class="bear-replies-expand comment-children">
        <?php $this->threadedComments(); ?>
        </div>
    <?php } ?>
    
    	</div>
				</div>
		
		



    <?php
    }
    
    
    
   
  
    private function getParent(){
        $db = \Typecho\Db::get();
        $parentID = $db->fetchRow($db->select('parent')->from('table.comments')->where('coid = ?', $this->coid));
        $parentID=$parentID['parent'];
        if($parentID=='0'){ return '';}
        else {
            $author=$db->fetchRow($db->select()->from('table.comments')->where('coid = ?', $parentID));
            if (empty($author['author']) ||!array_key_exists('author', $author) )
                $author['author'] = '已删除的评论';
                
            return '  <span>
								回复
								<a href="#comment-'.$parentID.'" class="bear-ruser" style="color:#000;  ">
									@ '.$author['author'].'
								</a>
								:
							</span> ';
        }
    }  

    
    
    /**
     * 获取当前评论链接
     *
     * @access protected
     * @return string
     */
    protected function ___permalink() : string
    {

        if ($this->options->commentsPageBreak) {            
            $pageRow = array('permalink' => $this->parentContent['pathinfo'], 'commentPage' => $this->_currentPage);
            return Typecho_Router::url('comment_page',
                        $pageRow, $this->options->index) . '#' . $this->theId;
        }
        
        return $this->parentContent['permalink'] . '#' . $this->theId;
    }

    /**
     * 子评论
     *
     * @access protected
     * @return array
     */
    protected function ___children()
    {
        return $this->options->commentsThreaded && !$this->isTopLevel && isset($this->_threadedComments[$this->coid]) 
            ? $this->_threadedComments[$this->coid] : array();
    }

    /**
     * 是否到达顶层
     *
     * @access protected
     * @return boolean
     */
    protected function ___isTopLevel()
    {
        return $this->levels > 0;
    }

    /**
     * 重载内容获取
     *
     * @access protected
     * @return void
     */
    protected function ___parentContent() : ?array
    {
        return $this->parameter->parentContent;
    }

    /**
     * 输出文章评论数
     *
     * @access public
     * @param string $string 评论数格式化数据
     * @return void
     */
    public function num()
    {
        $args = func_get_args();
        if (!$args) {
            $args[] = '%d';
        }

        $num = intval($this->_total);

        echo sprintf(isset($args[$num]) ? $args[$num] : array_pop($args), $num);
    }
  
  
    
    
    
    /**
     * 执行函数
     *
     * @access public
     * @return void
     */
    public function execute()
    {
        if (!$this->parameter->parentId) {
            return;
        }
$commentsAuthor = Typecho_Cookie::get('__typecho_remember_author');
        $commentsMail = Typecho_Cookie::get('__typecho_remember_mail');

        // 对已登录用户显示待审核评论，方便前台管理
        if ($this->user->hasLogin()) {
            $select = $this->select()->where('table.comments.cid = ?', $this->parameter->parentId)
                ->where('table.comments.status = ? OR table.comments.status = ?', 'approved', 'waiting');
        } else {
            $select = $this->select()->where('table.comments.cid = ?', $this->parameter->parentId)
                ->where('table.comments.status = ? OR (table.comments.author = ? AND table.comments.mail = ? AND table.comments.status = ?)', 'approved', $commentsAuthor, $commentsMail, 'waiting');
        }
        

        $threadedSelect = NULL;
        
        if ($this->options->commentsShowCommentOnly) {
            $select->where('table.comments.type = ?', 'comment');
        }
        
        $select->order('table.comments.coid', 'ASC');
        $this->db->fetchAll($select, array($this, 'push'));
        
        /** 需要输出的评论列表 */
        $outputComments = array();
        
        /** 如果开启评论回复 */
        if ($this->options->commentsThreaded) {
        
            foreach ($this->stack as $coid => &$comment) {
                
                /** 取出父节点 */
                $parent = $comment['parent'];
            
                /** 如果存在父节点 */
                if (0 != $parent && isset($this->stack[$parent])) {
                
                    /** 如果当前节点深度大于最大深度, 则将其挂接在父节点上 */
                    if ($comment['levels'] >= 2) {
                        $comment['levels'] = $this->stack[$parent]['levels'];
                        $parent = $this->stack[$parent]['parent'];     // 上上层节点
                        $comment['parent'] = $parent;
                    }
                
                    /** 计算子节点顺序 */
                    $comment['order'] = isset($this->_threadedComments[$parent]) 
                        ? count($this->_threadedComments[$parent]) + 1 : 1;
                
                    /** 如果是子节点 */
                    $this->_threadedComments[$parent][$coid] = $comment;
                } else {
                    $outputComments[$coid] = $comment;
                }
                
            }
        
            $this->stack = $outputComments;
        }
        
        /** 评论排序 */
        if ('DESC' == $this->options->commentsOrder) {
            $this->stack = array_reverse($this->stack, true);
        }
        
        /** 评论总数 */
        $this->_total = count($this->stack);
        
        /** 对评论进行分页 */
        if ($this->options->commentsPageBreak) {
            if ('last' == $this->options->commentsPageDisplay && !$this->parameter->commentPage) {
                $this->_currentPage = ceil($this->_total / $this->options->commentsPageSize);
            } else {
                $this->_currentPage = $this->parameter->commentPage ? $this->parameter->commentPage : 1;
            }
            
            /** 截取评论 */
            $this->stack = array_slice($this->stack,
                ($this->_currentPage - 1) * $this->options->commentsPageSize, $this->options->commentsPageSize);
            
            /** 评论置位 */
            $this->row = current($this->stack);
            $this->length = count($this->stack);
        }
        
        reset($this->stack);
    }

    /**
     * 将每行的值压入堆栈
     *
     * @access public
     * @param array $value 每行的值
     * @return array
     */
    public function push(array $value) : array
    {
        $value = $this->filter($value);
        
        /** 计算深度 */
        if (0 != $value['parent'] && isset($this->stack[$value['parent']]['levels'])) {
            $value['levels'] = $this->stack[$value['parent']]['levels'] + 1;
        } else {
            $value['levels'] = 0;
        }

        $value['realParent'] = $value['parent'];

        /** 重载push函数,使用coid作为数组键值,便于索引 */
        $this->stack[$value['coid']] = $value;
        $this->_commentAuthors[$value['coid']] = $value['author'];
        $this->length ++;
        
        return $value;
    }

    /**
     * 输出分页
     *
     * @access public
     * @param string $prev 上一页文字
     * @param string $next 下一页文字
     * @param int $splitPage 分割范围
     * @param string $splitWord 分割字符
     * @param string $template 展现配置信息
     * @return void
     */
    public function pageNav($prev = '&laquo;', $next = '&raquo;', $splitPage = 3, $splitWord = '...', $template = '')
    {
        if ($this->options->commentsPageBreak && $this->_total > $this->options->commentsPageSize) {
            $default = array(
                'wrapTag'       =>  'ol',
                'wrapClass'     =>  'page-navigator'
            );

            if (is_string($template)) {
                parse_str($template, $config);
            } else {
                $config = $template;
            }

            $template = array_merge($default, $config);

            $pageRow = $this->parameter->parentContent;
            $pageRow['permalink'] = $pageRow['pathinfo'];

            $query = Typecho_Router::url('comment_page', $pageRow, $this->options->index);

            /** 使用盒状分页 */
            $nav = new Typecho_Widget_Helper_PageNavigator_Box($this->_total,
                $this->_currentPage, $this->options->commentsPageSize, $query);
            $nav->setPageHolder('commentPage');
            $nav->setAnchor('comments');
            
            echo '<' . $template['wrapTag'] . (empty($template['wrapClass']) 
                    ? '' : ' class="' . $template['wrapClass'] . '"') . '>';
            $nav->render($prev, $next, $splitPage, $splitWord, $template);
            echo '</' . $template['wrapTag'] . '>';
        }
    }

    /**
     * 递归输出评论
     *
     * @access protected
     * @return void
     */
    public function threadedComments()
    {
        $children = $this->children;
        if ($children) {
            //缓存变量便于还原
            $tmp = $this->row;
            $this->sequence ++;

            //在子评论之前输出
            echo $this->_singleCommentOptions->before;

            foreach ($children as $child) {
                $this->row = $child;
                $this->threadedCommentsCallback();
                $this->row = $tmp;
            }

            //在子评论之后输出
            echo $this->_singleCommentOptions->after;

            $this->sequence --;
        }
    }
    
    /**
     * 列出评论
     * 
     * @access private
     * @param mixed $singleCommentOptions 单个评论自定义选项
     * @return void
     */
    public function listComments($singleCommentOptions = NULL)
    {
        //初始化一些变量
        $this->_singleCommentOptions = Typecho_Config::factory($singleCommentOptions);
        $this->_singleCommentOptions->setDefault(array(
            'before'        =>  '<ol class="comment-list">',
            'after'         =>  '</ol>',
            'beforeAuthor'  =>  '',
            'afterAuthor'   =>  '',
            'beforeDate'    =>  '',
            'afterDate'     =>  '',
            'dateFormat'    =>  $this->options->commentDateFormat,
            'replyWord'     =>  '回复',
            'commentStatus' =>  '评论正等待审核!',
            'avatarSize'    =>  32,
            'defaultAvatar' =>  NULL
        ));
        $this->pluginHandle()->trigger($plugged)->listComments($this->_singleCommentOptions, $this);

        if (!$plugged) {
            if ($this->have()) { 
                echo $this->_singleCommentOptions->before;
            
                while ($this->next()) {
                    $this->threadedCommentsCallback();
                }
            
                echo $this->_singleCommentOptions->after;
            }
        }
    }
    
    /**
     * 重载alt函数,以适应多级评论
     * 
     * @access public
     * @return void
     */
    public function alt(...$args)
    {
        $args = func_get_args();
        $num = func_num_args();
        
        $sequence = $this->levels <= 0 ? $this->sequence : $this->order;
        
        $split = $sequence % $num;
        echo $args[(0 == $split ? $num : $split) -1];
    }

    /**
     * 根据深度余数输出
     *
     * @access public
     * @param string $param 需要输出的值
     * @return void
     */
    public function levelsAlt()
    {
        $args = func_get_args();
        $num = func_num_args();
        $split = $this->levels % $num;
        echo $args[(0 == $split ? $num : $split) -1];
    }
    
    /**
     * 评论回复链接
     * 
     * @access public
     * @param string $word 回复链接文字
     * @return void
     */
    public function reply($word = '')
    {
        if ($this->options->commentsThreaded && $this->parameter->allowComment) {
            $word = empty($word) ? '回复' : $word;
            $this->pluginHandle()->trigger($plugged)->reply($word, $this);
            
            if (!$plugged) {
                
					
					
                echo '<a class="bear-action-link" href="' . substr($this->permalink, 0, - strlen($this->theId) - 1) . '?replyTo=' . $this->coid .
                    '#' . $this->parameter->respondId . '" rel="nofollow" onclick="return TypechoComment.reply(\'' .
                    $this->theId . '\', ' . $this->coid . ');">
						<span class="bear-action-icon" style="color:var(--bearhoney-accent-color)">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM5.76282 17H20V5H4V18.3851L5.76282 17ZM11 10H13V12H11V10ZM7 10H9V12H7V10ZM15 10H17V12H15V10Z"></path></svg>
						</span>
						<span class="bear-action-icon bear-action-icon-solid" style="color:var(--bearhoney-accent-color)">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM7 10V12H9V10H7ZM11 10V12H13V10H11ZM15 10V12H17V10H15Z"></path></svg>
						</span>
						
					</a>';
            }
        }
    }
    
    
    
    
    /**
     * 取消评论回复链接
     * 
     * @access public
     * @param string $word 取消回复链接文字
     * @return void
     */
    public function cancelReply($word = '')
    {
        if ($this->options->commentsThreaded) {
            $word = empty($word) ? ' 取消回复' : $word;
            $this->pluginHandle()->trigger($plugged)->cancelReply($word, $this);
            
            if (!$plugged) {
                $replyId = $this->request->filter('int')->replyTo;
                echo '<a type="button" class="el-button bear-cancel el-button--default el-button--small cancel-comment-reply" id="cancel-comment-reply-link" href="' . $this->parameter->parentContent['permalink'] . '#' . $this->parameter->respondId .
                '" rel="nofollow"' . ($replyId ? '' : ' style="display:none;"') . ' onclick="return TypechoComment.cancelReply();"><span>取消</span></a>';
            }
        }
    }
}