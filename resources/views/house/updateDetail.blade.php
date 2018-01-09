@extends('layouts.default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('house/css/H-ui.min.css')}}" />
    <style type="text/css">

        .shade {
            position: absolute;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.55);
        }

        .shade div {
            width: 300px;
            height: 200px;
            line-height: 200px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -100px;
            margin-left: -150px;
            background: white;
            border-radius: 5px;
            text-align: center;
        }

        .a-upload {
            padding: 4px 10px;
            height: 20px;
            line-height: 20px;
            position: relative;
            cursor: pointer;
            color: #888;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            display: inline-block;
            *display: inline;
            *zoom: 1
        }

        .a-upload input {
            position: absolute;
            font-size: 100px;
            right: 0;
            top: 0;
            opacity: 0;
            filter: alpha(opacity=0);
            cursor: pointer
        }

        .a-upload:hover {
            color: #444;
            background: #eee;
            border-color: #ccc;
            text-decoration: none
        }
        .img_div{min-height: 100px; min-width: 100px;}
        .isImg{width: 120px; height: 120px; position: relative; float: left; margin-left: 10px;}
        .removeBtn{position: absolute; top: 0; right: 0; z-index: 11; border: 0px; border-radius: 50px; background: red; width: 22px; height: 22px; color: white;}
        .shadeImg{position: absolute;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 15;
            text-align: center;
            background: rgba(0, 0, 0, 0.55);}
        .showImg{width: 400px; height: 500px; margin-top: 140px;}
    </style>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <a href="{{url('house/updateList')}}">点击前往列表查看</a>
        </div>
    @endif

    <div class="box">
        <div class="box-body">


            <div class="page-container">
                <form action="{{url('house/updateList/uSave')}}" method="post" id="SUBMIT" class="form form-horizontal" id="form-article-add" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$houseMsg->msgid}}" name="msgId">
                    <input type="hidden" value="{{$houseMsg->landid}}" name="landId">
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>房源类型：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                        <span class="select-box">
				            <select name="house_type" class="select" id="houseTypeVal">
                                {!! $optionStr !!}}
                            </select>
                        </span>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">详细位置：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_location" id="house_location" placeholder="广东省深圳市宝安区西乡街道56栋33号" value="{{$houseMsg->house_location}}" class="input-text">
                        </div>
                        <span id="house_locationMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源结构：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_structure" id="house_structure" placeholder="平面" value="{{$houseMsg->house_structure}}" class="input-text">
                        </div>
                        <span id="house_structureMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">周边信息：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <?php
                                $rimMessage = $houseMsg->rim_message ? explode(',',$houseMsg->rim_message) : "";
                                //取步行时间
                                if(is_array($rimMessage)){
                                    list(,$supermarket) = isset($rimMessage[0]) ? explode(' ',$rimMessage[0]) : '';
                                    list(,$Chinese) = isset($rimMessage[1]) ? explode(' ',$rimMessage[1]) : '';
                                    list(,$police) = isset($rimMessage[2]) ? explode(' ',$rimMessage[2]) : '';
                                    list(,$public) = isset($rimMessage[3]) ? explode(' ',$rimMessage[3]) : '';
                                } else {
                                    $supermarket = '';
                                    $Chinese = '';
                                    $police = '';
                                    $public = '';
                                }

                            ?>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='超市 {{$supermarket}}' @if(isset($rimMessage[0])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-1">
                                <label for="peripheral-1">超市</label>
                                <input type="number" name="" id="supermarket" @if(!isset($rimMessage[0])) disabled="disabled" @endif  value="{{$supermarket}}" min="1" max="300" class="input-text information">/分钟
                            </div>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='中餐馆 {{$Chinese}}' @if(isset($rimMessage[1])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-2">
                                <label for="peripheral-2">中&nbsp;餐&nbsp;馆</label>&nbsp;
                                <input type="number" name=""  @if(!isset($rimMessage[1])) disabled="disabled" @endif value="{{$Chinese}}" min="1" max="300" class="input-text information">/分钟
                            </div>
                            <br>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='警局 {{$police}}' @if(isset($rimMessage[2])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-3">
                                <label for="peripheral-3">警局</label>
                                <input type="number" name=""  @if(!isset($rimMessage[2])) disabled="disabled" @endif value="{{$police}}" min="0" max="300" class="input-text information">/分钟
                            </div>
                            <div class="check-box">
                                <input name="peripheral_information[]" value='公共交通 {{$public}}' @if(isset($rimMessage[3])) checked="checked" @endif type="checkbox" class="date_checkbox" id="peripheral-4">
                                <label for="peripheral-4">公共交通</label>
                                <input type="number" name="" @if(!isset($rimMessage[3])) disabled="disabled" @endif value="{{$public}}" min="1" max="300" class="input-text information">/分钟
                            </div>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源价格：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="number" name="house_price" id="house_price" placeholder="" value="{{$houseMsg->house_price}}"  min="0.0" step="0.1"class="input-text" style="width:95%;">元
                        </div>
                        <span id="house_priceMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源大小：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="number" name="house_size" id="house_size" placeholder="" value="{{$houseMsg->house_size}}"  min="0.0" step="0.1"class="input-text" style="width:95%;">平方
                        </div>
                        <span id="house_sizeMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">押金：</label>
                        <div class="formControls col-xs-8 col-sm-9"  style="width:45%;">
                            <input type="number" name="cash_pledge" id="cash_pledge" placeholder="" value="{{$houseMsg->cash_pledge}}"  min="1" class="input-text" style="width:95%;">平方
                        </div>
                        <span id="cash_pledgeMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>预付款比例：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <span class="select-box">
				                <select name="payment_proportion" class="select" id="payment_proportion">
                                    <option value="一押一租">一押一租</option>
                                </select>
				            </span>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>结算方式：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <span class="select-box">
				                <select name="knot_way" class="select" id="knot_way">
                                    <option value="月结">月结</option>
                                    <option value="季结">季结</option>
                                </select>
				            </span>
                        </div>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房屋设备：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                            <?php
                                if(empty($houseMsg->house_facility)){
                                    $equipment = array();
                                    $washing = in_array('洗衣机',$equipment);//洗衣机
                                    $air = in_array('空调',$equipment);//空调
                                    $heating = in_array('暖气',$equipment);//暖气
                                    $bed = in_array('床',$equipment);//床
                                    $kitchen = in_array('厨房',$equipment);//厨房
                                    $closet = in_array('衣柜',$equipment);//衣柜
                                    $refrigerator = in_array('冰箱',$equipment);//冰箱
                                }else{
                                    $equipment = explode(',',$houseMsg->house_facility);
                                    $washing = in_array('洗衣机',$equipment);//洗衣机
                                    $air = in_array('空调',$equipment);//空调
                                    $heating = in_array('暖气',$equipment);//暖气
                                    $bed = in_array('床',$equipment);//床
                                    $kitchen = in_array('厨房',$equipment);//厨房
                                    $closet = in_array('衣柜',$equipment);//衣柜
                                    $refrigerator = in_array('冰箱',$equipment);//冰箱
                                }
                            ?>
                            <div class="check-box">
                                <input name="house_facility[]" @if($washing) checked="checked" @endif value='洗衣机' type="checkbox" id="checkbox-1">
                                <label for="checkbox-1">洗衣机</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if($air) checked="checked" @endif value='空调' type="checkbox" id="checkbox-2">
                                <label for="checkbox-2">空调</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if($heating) checked="checked" @endif value='暖气' type="checkbox" id="checkbox-3">
                                <label for="checkbox-3">暖气</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if($bed) checked="checked" @endif value='床' type="checkbox" id="checkbox-4">
                                <label for="checkbox-4">床</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if($kitchen) checked="checked" @endif value='厨房' type="checkbox" id="checkbox-5">
                                <label for="checkbox-5">厨房</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if($closet) checked="checked" @endif value='衣柜' type="checkbox" id="checkbox-6">
                                <label for="checkbox-6">衣柜</label>
                            </div>
                            <div class="check-box">
                                <input name="house_facility[]" @if($refrigerator) checked="checked" @endif value='冰箱' type="checkbox" id="checkbox-7">
                                <label for="checkbox-7">冰箱</label>
                            </div>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">关键字：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="house_keyword" id="house_keyword" placeholder="多个关键字用英文逗号隔开，限10个关键字" value="{{$houseMsg->house_keyword}}" maxlength="10" class="input-text">
                        </div>
                        <span id="house_keywordMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房源简介：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <textarea name="house_brief" id="house_brief" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符">{{$houseMsg->house_brief}}</textarea>
                        </div>
                        <span id="house_briefMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">租期时长：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                            <div class="check-box">
                                <input type="text" name="house_rise" id="house_rise" placeholder="" value="{{$houseMsg->house_rise}}" class="input-text" style="display:inline-block">
                            </div>
                            <span>起租期</span>
                            <div class="check-box">
                                <input type="text" name="house_duration" id="house_duration" value="{{$houseMsg->house_duration}}" class="input-text Wdate">
                            </div>
                            <span>最长租期</span>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房屋状态：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '预租') checked="true" @endif value="预租" type="radio" id="radio-1">
                                <label for="radio-1">预租</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '已锁定') checked="true" @endif value="已锁定" type="radio" id="radio-2">
                                <label for="radio-2">已锁定</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '已出租') checked="true" @endif value="已出租" type="radio" id="radio-4">
                                <label for="radio-4">已出租</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '配置中') checked="true" @endif value="配置中" type="radio" id="radio-5">
                                <label for="radio-5">配置中</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '停租') checked="true" @endif value="停租" type="radio" id="radio-6">
                                <label for="radio-6">停租</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '冻结') checked="true" @endif value="冻结" type="radio" id="radio-7">
                                <label for="radio-7">冻结</label>
                            </div>
                            <div class="check-box">
                                <input name="house_status" @if($houseMsg->house_status == '暂停出租') checked="true" @endif value="暂停出租" type="radio" id="radio-8">
                                <label for="radio-8">暂停出租</label>
                            </div>

                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东姓名：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_name" value="{{$houseMsg->landlord_name}}" id="landlord_name" class="input-text Wdate" style="width:220px;">
                        </div>
                        <span id="landlord_nameMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东证件号：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_identity" value="{{$houseMsg->landlord_identity}}" id="landlord_identity" class="input-text Wdate" style="width:220px;">
                        </div>
                        <span id="landlord_identityMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东邮箱：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_email" value="{{$houseMsg->landlord_email}}" id="landlord_email" class="input-text Wdate" style="width:220px;">
                        </div>
                        <span id="landlord_emailMsg"></span>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东电话：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_phone" value="{{$houseMsg->landlord_phone}}" id="landlord_phone" class="input-text Wdate" style="width:220px;">
                        </div>
                        <span id="landlord_phoneMsg"></span>
                    </div>

                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东性别：</label>
                        <div class="formControls col-xs-8 col-sm-9 skin-minimal">

                            <div class="check-box">
                                <input name="landlord_sex" @if($houseMsg->landlord_sex == '男') checked="true" @endif value="男" type="radio" id="radioTwo-1">
                                <label for="radioTwo-1">男</label>
                            </div>
                            <div class="check-box">
                                <input name="landlord_sex" @if($houseMsg->landlord_sex == '女') checked="true" @endif value="女" type="radio" id="radioTwo-2">
                                <label for="radioTwo-2">女</label>
                            </div>
                            <div class="check-box">
                                <input name="landlord_sex" @if($houseMsg->landlord_sex == '未知') checked="true" @endif value="未知>" type="radio" id="radioTwo-3">
                                <label for="radioTwo-3">未知</label>
                            </div>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东联系地址：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <input type="text" name="landlord_site" value="{{$houseMsg->landlord_site}}" id="datemin" class="input-text Wdate">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">房东备注：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <textarea name="landlord_remark" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符">{{$houseMsg->landlord_remark}}</textarea>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">图片操作：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="width:45%;">
                            <table>
                                @foreach($imgArr as $value)
                                <tr id="tr_{{$value->imgid}}">

                                    <td>
                                        <img style="width:80px; height:120px;" src="{{asset('./uploads')}}/{{$value->house_imagename}}" alt="">
                                        <a href="javascript:delimage({{$value->imgid}});" >删除此图片</a>
                                    </td>

                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">选择图片：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <a href="javascript:;" class="a-upload" style="width:15%;height:30px;">
                                <input type="file" name="upload[]" id="myFile" multiple="multiple"/><span>点击这里上传文件</span>
                            </a>
                            <div class="img_div"></div>

                            <div class="shade" onclick="javascript:closeShade()">
                                <div class="">
                                    <span class="text_span"></span>
                                </div>
                            </div>

                            <div class="shadeImg" onclick="javascript:closeShadeImg()">
                                <div class="">
                                    <img class="showImg" src=""/>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row cl">
                        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                            <button class="btn btn-primary radius" type="submit" id="verification">保存并提交审核</button>
                            <a href="javascript:window.history.go(-1);"><button class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></a>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>

@stop

@section('js')

    {{--日期用--}}
    <script type="text/javascript" src="{{asset('house/laydate/laydate.js')}}" ></script>
    <script>
        //常规用法
        laydate.render({
            elem: '#house_rise'
        });
        laydate.render({
            elem: '#house_duration'
        });
    </script>
    <script src="{{asset('house/js/jquery.min.js')}}"></script>
    <script src="{{asset('house/js/H-ui.js')}}"></script>
    <script type="text/javascript">
        function delimage(imageid) {
            $.ajax({
                url:"{{url('house/updateList/del')}}",
                data:'id='+imageid,
                type:'get',
                success:function (re) {
                    if (re != '0') {
                        $("#tr_"+imageid).remove();
                    }
                }
            })
        }

    </script>
    <script>
        $(function (){
            $(".date_checkbox").change(function (){
                if(this.checked){
                    $(this).next().next().removeProp('disabled');
                }else{
                    $(this).next().next().prop('disabled',true);
                    $(this).next().next().val('');
                }
            })
        });
        $(function (){
            $(".information").blur(function (){
                var checkbox = $(this).prev().prev();
                var timeVal = $(this).val();
                var val = checkbox.val();
                var arr=val.split(" ");
                var str = arr[0];
                checkbox.val(str+' '+timeVal);
            });
        });

    </script>
    <script>
        document.getElementById('houseTypeVal').value='{{$houseMsg->house_type}}';
        document.getElementById('payment_proportion').value='{{$houseMsg->payment_proportion}}';
        document.getElementById('knot_way').value='{{$houseMsg->knot_way}}';
    </script>
    <script type="text/javascript">
        $(function() {
            var objUrl;
            var img_html;
            $("#myFile").change(function() {
                var img_div = $(".img_div");
                var filepath = $("input[name='upload[]']").val();
                for(var i = 0; i < this.files.length; i++) {
                    objUrl = getObjectURL(this.files[i]);
                    var extStart = filepath.lastIndexOf(".");
                    var ext = filepath.substring(extStart, filepath.length).toUpperCase();
                    /*
                     作者：z@qq.com
                     时间：2016-12-10
                     描述：鉴定每个图片上传尾椎限制
                     * */
                    if(ext != ".BMP" && ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
                        $(".shade").fadeIn(500);
                        $(".text_span").text("图片限于bmp,png,gif,jpeg,jpg格式");
                        this.value = "";
                        $(".img_div").html("");
                        return false;
                    } else {
                        /*
                         若规则全部通过则在此提交url到后台数据库
                         * */
                        img_html = "<div class='isImg'><img src='" + objUrl + "' onclick='javascript:lookBigImg(this)' style='height: 100%; width: 100%;' /><button class='removeBtn' onclick='javascript:removeImg(this)'>x</button></div>";
                        img_div.append(img_html);
                    }
                }
                /*
                 作者：z@qq.com
                 时间：2016-12-10
                 描述：鉴定每个图片大小总和
                 * */
                var file_size = 0;
                var all_size = 0;
                for(j = 0; j < this.files.length; j++) {
                    file_size = this.files[j].size;
                    all_size = all_size + this.files[j].size;
                    var size = all_size / 1024;
                    if(size > 5000) {
                        $(".shade").fadeIn(5000);
                        $(".text_span").text("上传的图片大小不能超过1000k！");
                        this.value = "";
                        $(".img_div").html("");
                        return false;
                    }
                }
                /*
                 描述：鉴定每个图片宽高 以后会做优化 多个图片的宽高 暂时隐藏掉 想看效果可以取消注释就行
                 * */
                //					var img = new Image();
                //					img.src = objUrl;
                //					img.onload = function() {
                //						if (img.width > 100 && img.height > 100) {
                //							alert("图片宽高不能大于一百");
                //							$("#myFile").val("");
                //							$(".img_div").html("");
                //							return false;
                //						}
                //					}
                return true;
            });
            /*
             作者：z@qq.com
             时间：2016-12-10
             描述：鉴定每个浏览器上传图片url 目前没有合并到Ie
             * */
            function getObjectURL(file) {
                var url = null;
                if(window.createObjectURL != undefined) { // basic
                    url = window.createObjectURL(file);
                } else if(window.URL != undefined) { // mozilla(firefox)
                    url = window.URL.createObjectURL(file);
                } else if(window.webkitURL != undefined) { // webkit or chrome
                    url = window.webkitURL.createObjectURL(file);
                }
                //console.log(url);
                return url;
            }
        });
        /*
         描述：上传图片附带删除 再次地方可以加上一个ajax进行提交到后台进行删除
         * */
        function removeImg(r){
            $(r).parent().remove();
        }
        /*
         描述：上传图片附带放大查看处理
         * */
        function lookBigImg(b){
            $(".shadeImg").fadeIn(500);
            $(".showImg").attr("src",$(b).attr("src"))
        }
        /*
         描述：关闭弹出层
         * */
        function closeShade(){
            $(".shade").fadeOut(500);
        }
        /*
         描述：关闭弹出层
         * */
        function closeShadeImg(){
            $(".shadeImg").fadeOut(500);
        }
    </script>
@stop

