<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li class="active"><a href="javascript:;">淘宝宝贝助手</a></li>
</ul>

<div class="main">
    <form id="dataform" action="" method="post" class="form-horizontal form">
        <div class="panel panel-default">
            <div class="panel-heading">
                淘宝宝贝助手
            </div>
            <div class="panel-body">

                <div class='alert alert-danger'>尽量在服务器空闲时间来操作，会占用大量内存与带宽，在获取过程中，请不要进行任何操作!</div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span> 链接 或 itemId</label>
                    <div class="col-sm-9">
                        <textarea style="width:600px;height:200px" id="url" name="url" class="form-control"></textarea>
                        <span class="help-block">商品连接, 例如: http://item.taobao.com/item.htm?id=xxxxxx 或 http://detail.tmall.com/item.htm?id=xxxxx</span>
                        <span class="help-block">商品itemID, 上面连接中的 xxxxxxx</span>
                        <span class="help-block">每一行一个itemID 或链接</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span> 设置分类</label>

                    <div class="col-sm-9">
                           <?php  if($shopset['catlevel']==3) { ?>
        <?php  echo tpl_form_field_category_3level('category', $parent, $children,0,0,0)?>
        <?php  } else { ?>
        <?php  echo tpl_form_field_category_2level('category', $parent, $children,0,0)?>
        <?php  } ?>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"> </label>
                    <div class="col-sm-9">
                        <span class="help-block">此分类读取的是商城的商品分类, 设置默认抓取商品的分类</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group col-sm-12">
            <input id="btn_submit" type="button"  value="立即获取" class="btn btn-primary col-lg-12"  onclick="formcheck()"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        </div>
    </form>
</div>
<script type="text/javascript">
 
    var category = <?php  echo json_encode($children)?>;
    $("#dataform").ajaxForm({dataType:"json"});
    var len = 0;
    var urls = [];
    var total = 0;
    function formcheck() {
      
        if ($(":input[name='url']").val() == '') {
            Tip.focus(":input[name='url']", "请输入商品链接或itemId!", "right");
            return;
        }
            <?php  if($shopset['catlevel']==3) { ?>
            if($('#cate_3').val()=='0'){
            Tip.focus("#cate_3", "请选择完整宝贝分类!");
            return;
        }
            <?php  } else { ?>
        if($('#cate_2').val()=='0'){
            Tip.focus("#cate_2", "请选择完整宝贝分类!");
            return;
        }
        <?php  } ?>
        $("#dataform").attr("disabled", "true");
        $("#btn_submit").val("正在获取中...").removeClass("btn-primary").attr("disabled", "true");

        urls = $("#url").val().split('\n');
        total = urls.length;
        $("#btn_submit").val("检测到需要获取 " + total + " 个宝贝, 请等待开始....");
        fetch_next();
        return;
    }
    function fetch_next() {
        var postdata =  {
                    url: urls[len],
                    pcate: $("select[name='category[parentid]']").val(),
                    ccate: $("select[name='category[childid]']").val()
                };
               <?php  if($shopset['catlevel']==3) { ?>
                  postdata.tcate = $("select[name='category[thirdid]']").val();
               <?php  } ?>
        $.post("<?php  echo $this->createPluginWebUrl('taobao/fetch')?>",
               postdata,
        function (data) {
             
            len++;
            $("#btn_submit").val("已经获取  " + len + " / " + total + " 个宝贝, 请等待....");

            if (len >= total) {
                $("#btn_submit").val("立即获取").addClass("btn-primary").removeAttr("disabled");
                if (confirm('商品已经获取成功, 是否跳转到商品管理?')) {
                      location.href = "<?php  echo $this->createWebUrl('shop/goods')?>";
                }
                else {
                     location.reload();
                }
            }
            else {
                fetch_next();
            }

        }, "json");
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
