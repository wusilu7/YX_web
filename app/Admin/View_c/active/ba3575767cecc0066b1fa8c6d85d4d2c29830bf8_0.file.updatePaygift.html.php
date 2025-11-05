<?php
/* Smarty version 3.1.30, created on 2024-09-28 17:04:58
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\active\updatePaygift.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66f7c6ba5c02b7_74755308',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ba3575767cecc0066b1fa8c6d85d4d2c29830bf8' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\active\\updatePaygift.html',
      1 => 1727512703,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66f7c6ba5c02b7_74755308 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.07.sa.css" rel="stylesheet">
<style>
    .fee_rate{
        display:none
    }
    .show_input{

    }
</style>
<div class="jin-content-title"><span>付费礼包修改</span></div>
<hr />
<div class="form-horizontal col-sm-6 col-sm-offset-3">
    <div class="form-group">
        <label for="ID" class="col-sm-2 control-label">编号</label>
        <div class="col-sm-10">
            <input id="ID" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="Name" class="col-sm-2 control-label">礼包名</label>
        <div class="col-sm-5">
            <input id="Name" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="Tip" class="col-sm-2 control-label">礼包描述</label>
        <div class="col-sm-5">
            <!--<input id="Tip" class="form-control"/>-->
            <textarea id="Tip" cols="40" rows="7"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="Icon" class="col-sm-2 control-label">礼包Icon</label>
        <div class="col-sm-5">
            <input id="Icon" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="IsOpen" class="col-sm-2 control-label">是否开放</label>
        <div class="col-sm-10">
            <select id="IsOpen" style="height: 34px;line-height: 34px; width: 100px; padding: 0 8px;">
                <option value="1">开放</option>
                <option value="0">关闭</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="Type" class="col-sm-2 control-label">类型</label>
        <div class="col-sm-10">
            <select id="Type" style="height: 34px;line-height: 34px; width: 100px; padding: 0 8px;">
                <option value="2">特权礼包</option>
                <option value="1">月度特惠</option>
                <option value="0">每日礼包</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="InitPrice" class="col-sm-2 control-label">初始价格</label>
        <div class="col-sm-10">
            <input id="InitPrice" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="PayType" class="col-sm-2 control-label">付费类型</label>
        <div class="col-sm-10">
            <select id="PayType" style="height: 34px;line-height: 34px; width: 100px; padding: 0 8px;">
                <option value="1">游戏货币</option>
                <option value="0" selected="selected">人民币</option>
            </select>
        </div>
    </div>
    <div class="form-group Cost">
        <label for="Cost" class="col-sm-2 control-label">消耗货币</label>
        <div class="col-sm-10">
            <input id="Cost" type="number"  class="form-control">
        </div>
    </div>
    <div class="form-group Price">
        <label for="Price" class="col-sm-2 control-label">消耗人民币</label>
        <div class="col-sm-10">
            <input id="Price" type="number"  class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="ResetType" class="col-sm-2 control-label">重置类型</label>
        <div class="col-sm-10">
            <select id="ResetType" style="height: 34px;line-height: 34px; width: 100px; padding: 0 8px;">
                <option value="3">永久</option>
                <option value="2">每月</option>
                <option value="1">每周</option>
                <option value="0">每日</option>
                <option value="4">时间</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="LimitCount" class="col-sm-2 control-label">限购次数</label>
        <div class="col-sm-10">
            <input id="LimitCount" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="Fixed_OpenTime" class="col-sm-2 control-label">开启时间</label>
        <div class="col-sm-10">
            <input type="text" id="Fixed_OpenTime" class="form-control jin-datetime">
        </div>
    </div>
    <div class="form-group">
        <label for="OPS_OpenTime1" class="col-sm-2 control-label">开服xx后开启</label>
        <div class="col-sm-10">
            <input type="number" id="OPS_OpenTime1" style="height: 34px;line-height: 34px; width: 70px; text-align: center;">天
            <input type="number" id="OPS_OpenTime2" style="height: 34px;line-height: 34px; width: 70px; text-align: center;">时
            <input type="number" id="OPS_OpenTime3" style="height: 34px;line-height: 34px; width: 70px; text-align: center;">分
        </div>
    </div>
    <div class="form-group">
        <label for="Fixed_EndTime" class="col-sm-2 control-label">结束时间</label>
        <div class="col-sm-10">
            <input type="text" id="Fixed_EndTime" class="form-control jin-datetime">
        </div>
    </div>
    <div class="form-group">
        <label for="OPS_OpenTime1" class="col-sm-2 control-label">开服xx后关闭</label>
        <div class="col-sm-10">
            <input type="number" id="OPS_EndTime1" style="height: 34px;line-height: 34px; width: 70px; text-align: center;">天
            <input type="number" id="OPS_EndTime2" style="height: 34px;line-height: 34px; width: 70px; text-align: center;">时
            <input type="number" id="OPS_EndTime3" style="height: 34px;line-height: 34px; width: 70px; text-align: center;">分
        </div>
    </div>
    <div class="form-group">
        <label for="SKUIOS" class="col-sm-2 control-label">SKUIOS</label>
        <div class="col-sm-10">
            <input id="SKUIOS" type="text"  class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="SKUAndroid" class="col-sm-2 control-label">SKUAndroid</label>
        <div class="col-sm-10">
            <input id="SKUAndroid" type="text"  class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="UpdateTime" class="col-sm-2 control-label">UpdateTime</label>
        <div class="col-sm-3">
            <input id="UpdateTime" type="text"  class="form-control">
        </div>
        <a id="UpdateTime_add" class="btn btn-info jin_add"><span class="glyphicon glyphicon-plus"></span></a>
        <span style="color: orangered;">*********</span>
    </div>
    <div class="form-group">
        <label for="SuperValue" class="col-sm-2 control-label">超值</label>
        <div class="col-sm-10">
            <input id="SuperValue" type="text" class="form-control">
        </div>
    </div>
    <div class="form-group"  style="border: 1px solid red; margin: 10px 0;">
        <div class="form-group">
            <label for="forced_send_OtherReward" class="col-sm-2 control-label">强制发送</label>
            <input id="forced_send_OtherReward" value="1" type="checkbox" style="margin-left: 10px; width: 20px; height: 20px;">
            *当奖励设置为空,也会发送
        </div>
        <label for="OtherReward" class="col-sm-2 control-label">累计充值条件</label>
        <div class="col-sm-10">
            <!--<input id="Tip" class="form-control"/>-->
            <textarea id="OtherReward" cols="80" rows="3"></textarea>
        </div>
    </div>
    <h3>累计充值奖励<button id="showRewardRandPool" type="button" class="layui-btn layui-btn-xs">显示/隐藏</button></h3>
    <div id="RewardRandPoolStyle"  style="margin: 10px 0;">
        <div class="form-group">
            <label for="forced_send_RewardRandPool" class="col-sm-2 control-label">强制发送</label>
            <input id="forced_send_RewardRandPool" value="1" type="checkbox" style="margin-left: 10px; width: 20px; height: 20px;">
            *当奖励设置为空,也会发送
        </div>
        <div class="form-group">
            <label for="reward_type17" class="col-sm-2 control-label">累计充值奖励1</label>
            <div class="col-sm-10">
                <select id="reward_type17" style="width: 100px; height: 32px; line-height: 32px;">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son17">
                </div>
                数量:<input id="item_num17" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability17" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type18" class="col-sm-2 control-label">累计充值奖励2</label>
            <div class="col-sm-10">
                <select id="reward_type18" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son18">
                </div>
                数量:<input id="item_num18" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability18" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type19" class="col-sm-2 control-label">累计充值奖励3</label>
            <div class="col-sm-10">
                <select id="reward_type19" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son19">
                </div>
                数量:<input id="item_num19" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability19" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type20" class="col-sm-2 control-label">累计充值奖励4</label>
            <div class="col-sm-10">
                <select id="reward_type20" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son20">
                </div>
                数量:<input id="item_num20" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability20" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type21" class="col-sm-2 control-label">累计充值奖励5</label>
            <div class="col-sm-10">
                <select id="reward_type21" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son21">
                </div>
                数量:<input id="item_num21" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability21" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type22" class="col-sm-2 control-label">累计充值奖励6</label>
            <div class="col-sm-10">
                <select id="reward_type22" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son22">
                </div>
                数量:<input id="item_num22" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability22" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type23" class="col-sm-2 control-label">累计充值奖励7</label>
            <div class="col-sm-10">
                <select id="reward_type23" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son23">
                </div>
                数量:<input id="item_num23" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability23" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type24" class="col-sm-2 control-label">累计充值奖励8</label>
            <div class="col-sm-10">
                <select id="reward_type24" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son24">
                </div>
                数量:<input id="item_num24" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
                条件编号:<input id="probability24" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="条件编号">
            </div>
        </div>
    </div>
    <h3>充值物品<button id="showReward" type="button" class="layui-btn layui-btn-xs">显示/隐藏</button></h3>
    <div id="RewardStyle"  style="margin: 10px 0;">
        <div class="form-group">
            <label for="forced_send_Reward" class="col-sm-2 control-label">强制发送</label>
            <input id="forced_send_Reward" value="1" type="checkbox" style="margin-left: 10px; width: 20px; height: 20px;">
            *当奖励设置为空,也会发送
        </div>
        <div class="form-group">
            <label for="reward_type1" class="col-sm-2 control-label">实际奖励1</label>
            <div class="col-sm-10">
                <select id="reward_type1" style="width: 100px; height: 32px; line-height: 32px;">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son1">
                </div>
                数量:<input id="item_num1" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">*数量为空不配该奖励
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type2" class="col-sm-2 control-label">实际奖励2</label>
            <div class="col-sm-10">
                <select id="reward_type2" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son2">
                </div>
                数量:<input id="item_num2" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type3" class="col-sm-2 control-label">实际奖励3</label>
            <div class="col-sm-10">
                <select id="reward_type3" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son3">
                </div>
                数量:<input id="item_num3" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type4" class="col-sm-2 control-label">实际奖励4</label>
            <div class="col-sm-10">
                <select id="reward_type4" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son4">
                </div>
                数量:<input id="item_num4" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type5" class="col-sm-2 control-label">实际奖励5</label>
            <div class="col-sm-10">
                <select id="reward_type5" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son5">
                </div>
                数量:<input id="item_num5" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type6" class="col-sm-2 control-label">实际奖励6</label>
            <div class="col-sm-10">
                <select id="reward_type6" style="width: 100px; height: 32px; line-height: 32px;">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son6">
                </div>
                数量:<input id="item_num6" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type7" class="col-sm-2 control-label">实际奖励7</label>
            <div class="col-sm-10">
                <select id="reward_type7" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son7">
                </div>
                数量:<input id="item_num7" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
        <div class="form-group">
            <label for="reward_type8" class="col-sm-2 control-label">实际奖励8</label>
            <div class="col-sm-10">
                <select id="reward_type8" style="width: 100px; height: 32px; line-height: 32px; ">
                    <option value="[奖励道具]">[奖励道具]</option>
                    <option value="[奖励货币]">[奖励货币]</option>
                    <option value="[奖励时装]">[奖励时装]</option>
                    <option value="[奖励宠物]">[奖励宠物]</option>
                </select>
                <div style="display: inline-block" id="reward_type_son8">
                </div>
                数量:<input id="item_num8" style="width: 80px; height: 32px; line-height: 32px;" type="text" placeholder="数量">
            </div>
        </div>
    </div>
    <div class="btn-group center jin-sa-btn" style="margin-bottom: 30px">
        <button data-type="update" class="btn  btn-success">修改</button>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    var rewardsOptions = eval('<?php echo $_smarty_tpl->tpl_vars['money']->value;?>
').slice(1);
    var iii = 24;
    var UpdateTime=0;
    $(function () {
        extendedItemsTag({
            num: iii,
            options: rewardsOptions
        });
        $("#showShowReward").click(function () {
            $("#ShowRewardStyle").toggle()
        });

        $("#showReward").click(function () {
            $("#RewardStyle").toggle()
        });
    });
    function getSa() {
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=920",
            dataType: "json",
            success: function (json) {
                $("#ID").val(json.ID);
                $("#ID").attr('data-data-gi',json.gi);
                $("#ID").attr('data-data-sign',json.gi_sign);
                $("#Name").val(json.Name);
                $("#Tip").val(json.Tip);
                $("#IsOpen option[value='"+json.IsOpen+"']").attr("selected","selected");
                $("#Icon").val(json.Icon);
                $("#Type option[value='"+json.Type+"']").attr("selected","selected");
                $("#PayType option[value='"+json.PayType+"']").attr("selected","selected");
                $("#Cost").val(json.Cost);
                $("#InitPrice").val(json.InitPrice);
                $("#SuperValue").val(json.SuperValue);
                $("#Price").val(json.Price);
                $("#ResetType option[value='"+json.ResetType+"']").attr("selected","selected");
                $("#LimitCount").val(json.LimitCount);
                $("#SKUIOS").val(json.SKUIOS);
                $("#SKUAndroid").val(json.SKUAndroid);
                $("#UpdateTime").val(json.UpdateTime);
                $("#OtherReward").val(json.TotalRewardCon);
                UpdateTime = json.UpdateTime;
                FixedTime(json);
                $("#forced_send_RewardRandPool").prop("checked",parseInt(json.forced_send_TotalReward));
                $("#forced_send_OtherReward").prop("checked",parseInt(json.forced_send_TotalRewardCon));
                $("#forced_send_Reward").prop("checked",parseInt(json.forced_send_Reward));
                //付费类型事件
                if(json.PayType==0){
                    $(".Cost").hide();
                    $(".Price").show();
                }else{
                    $(".Price").hide();
                    $(".Cost").show();
                }
                $("#PayType").on('change',function () {
                    if($(this).val()==0){
                        $(".Cost").hide();
                        $(".Price").show();
                    }else{
                        $(".Price").hide();
                        $(".Cost").show();
                    }
                });
                if(json.Reward){
                    reward_shadow(json.Reward.split(';'),8);
                }else{
                    $("#RewardStyle").hide()
                }
                if(json.TotalReward){
                    reward_shadow(json.TotalReward.split(';'),24,17);
                }else{
                    $("#ShowRewardStyle").hide()
                }
            }
        });
        $(document).ready(calendar1('hour', '#Fixed_OpenTime', '#Fixed_EndTime'));
    }
    $('#UpdateTime_add').on('click', function () {
        $('#UpdateTime').val($('#UpdateTime').val()-0+1);
    });

    /* 礼包修改 */
    $('button[data-type="update"]').on('click', function () {
        // 每次点击都弹出确认框，检查是否需要增加UpdateTime
        layer.alert('请确认<span style="color:red;">UpdateTime</span>选项是否需要+1？', {
            icon: 0,
            shadeClose: true,
            btn: ['不需要', '需要'],
            // 点击“不需要”时的逻辑
            yes: function (index) {
                layer.close(index);
                all_update();
            },
            // 点击“需要”时的逻辑
            btn2: function (index) {
                layer.close(index);
                var currentUpdateTime = $("#UpdateTime").val();
                if (!currentUpdateTime || isNaN(currentUpdateTime)) {
                    layer.msg('UpdateTime字段无效，请输入有效数字！', {time: 800});
                    return;
                }
                $("#UpdateTime").val(parseInt(currentUpdateTime) + 1);
                all_update();
            }
        });
    });

    function all_update() {
        var OpenTime = '';
        var EndTime = '';

        // 构建OpenTime
        if ($("#Fixed_OpenTime").val() != '') {
            OpenTime += '[固定日期]=(' + $("#Fixed_OpenTime").val() + ',0);';
        }
        if ($('#OPS_OpenTime1').val() != '') {
            OpenTime += '[开服日期]=(' + $('#OPS_OpenTime1').val() + ',' + $('#OPS_OpenTime2').val() + ',' + $('#OPS_OpenTime3').val() + ',0);';
        }

        // 构建EndTime
        if ($("#Fixed_EndTime").val() != '') {
            EndTime += '[固定日期]=(' + $("#Fixed_EndTime").val() + ',0);';
        }
        if ($('#OPS_EndTime1').val() != '') {
            EndTime += '[开服日期]=(' + $('#OPS_EndTime1').val() + ',' + $('#OPS_EndTime2').val() + ',' + $('#OPS_EndTime3').val() + ',0);';
        }

        var t_reward1 = reward(8);
        var ShowReward1 = reward_rand(24, 17);

        if (t_reward1 == '') {
            layer.msg('请配置奖励!', {time: 800});
            return false;
        }
        if (OpenTime == '' || EndTime == '') {
            layer.msg('请配置时间!', {time: 800});
            return false;
        }

        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=913",
            data: {
                gi: $("#ID").attr('data-data-gi'),
                sign: $("#ID").attr('data-data-sign'),
                ID: $("#ID").val(),
                Name: $("#Name").val(),
                Tip: $("#Tip").val(),
                IsOpen: $("#IsOpen").val(),
                Icon: $("#Icon").val(),
                Type: $("#Type").val(),
                PayType: $("#PayType").val(),
                Cost: $("#Cost").val(),
                InitPrice: $("#InitPrice").val(),
                Price: $("#Price").val(),
                ResetType: $("#ResetType").val(),
                LimitCount: $("#LimitCount").val(),
                OtherReward: $("#OtherReward").val(),
                ShowReward1: ShowReward1,
                SuperValue: $("#SuperValue").val(),
                SKUIOS: $("#SKUIOS").val(),
                SKUAndroid: $("#SKUAndroid").val(),
                UpdateTime: $("#UpdateTime").val(),
                forced_send_RewardRandPool: $('#forced_send_RewardRandPool').is(':checked') ? $('#forced_send_RewardRandPool').val() : 0,
                forced_send_Reward: $('#forced_send_Reward').is(':checked') ? $('#forced_send_Reward').val() : 0,
                OpenTime: OpenTime,
                EndTime: EndTime,
                forced_send_OtherReward: $('#forced_send_OtherReward').is(':checked') ? $('#forced_send_OtherReward').val() : 0,
                Reward1: t_reward1
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff'] // 0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                layer.alert('成功', {icon: 1}, function (index) {
                    layer.close(index);
                    window.location.reload();
                });
            }
        });
    }
<?php echo '</script'; ?>
>
<?php }
}
