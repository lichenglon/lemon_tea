﻿@extends('layouts.default')

@section('css')
	<link rel="stylesheet" type="text/css" href="{{asset('house/css/H-ui.min.css')}}" />
@stop

@section('content')

	<div class="box">
		<div class="box-body" style="height:750px">


			<div class="Hui-article">
				<article class="cl pd-20">
					<form action="{{url('house/houseLister')}}" method="get">
						<span class="select-box inline" style="width:100%;">
								{{ csrf_field() }}
							<input type="hidden" name="hidden" value="1">

							<select name="type" class="input-text" id="findType" style="width:8%;">
								<option value="%">@lang('house_translate.classification')</option>

								@foreach($typeObject as $value)
									<option value="{{$value->name}}">{{$value->name}}</option>
								@endforeach
							</select>

							&nbsp;&nbsp;
							<select name="search_k" class="input-text" id="search_k" style="width:10%;">
								<option value="%">@lang('house_translate.Please_choose')</option>
								<option value="serial_number">@lang('house_translate.Room_number')</option>
								<option value="house_structure">@lang('house_translate.Housing_structure')</option>
								<option value="house_price">@lang('house_translate.The_price')</option>
								<option value="house_location">@lang('house_translate.Housing_location')</option>
							</select>
							&nbsp;
							<input type="text" name="search_v" class="input-text" id="search_v" style="width:15%;"/>

							&nbsp;&nbsp;
							<input type="submit" class="btn btn-default" name="find" value="@lang('house_translate.Determine')">

							<input type="submit" class="btn btn-default" name="export" value="@lang('house_translate.Export_Excel')">

							<span class="r">
							@lang('house_translate.Common_data')：<strong>{{$houseCount}}</strong> @lang('house_translate.strip')
						</span>
                        </span>
					</form>
					{{--<div class="cl pd-5 bg-1 bk-gray mt-20">


					</div>--}}
					<div class="mt-20">
						<table class="table table-border table-bordered table-bg table-hover table-sort">
							<thead>
							<tr class="text-c" id="theader">
								<th width="">@lang('house_translate.classification')</th>
								<th width="">@lang('house_translate.Room_number')</th>
								<th width="">@lang('house_translate.Housing_structure')</th>
								<th width="">@lang('house_translate.Housing_prices')</th>
								<th width="">@lang('house_translate.Housing_size')</th>
								<th width="">@lang('house_translate.House_equipment')</th>
								<th width="">@lang('house_translate.Housing_location')</th>
								<th width="">@lang('house_translate.The_lease_time')</th>
								<th width="">@lang('house_translate.Home_state')</th>
								<th width="">@lang('house_translate.Audit_status')</th>
								<th width="">@lang('house_translate.operation')</th>
							</tr>
							</thead>
							<tbody>
							@foreach($houseObj as $key => $val)
								<tr class="text-c">
									<td>{{$val->house_type}}</td>
									<td class="text-l"><a href="{{url('house/houseLister/detail',['id'=>$val->msgid])}}"><u style="cursor:pointer" class="text-primary" title="查看">{{$val->serial_number}}</u></a></td>
									<?php
										$house_structure = explode(',', $val->house_structure)
									?>
									<td>{{ $house_structure[0] }} @lang('house_translate.room')
										{{ $house_structure[1] }} @lang('house_translate.hall')
										{{ $house_structure[2] }} @lang('house_translate.kitchen')
										{{ $house_structure[3] }} @lang('house_translate.toilet')
									</td>
									<td>{{$val->house_price}}</td>
									<td><span>{{$val->house_size}}</span> /@lang('house_translate.Square_meters')</td>
									<td><?php $equipment = explode(',',$val->house_facility); foreach ($equipment as $value){ echo $value.'&nbsp;&nbsp;&nbsp;'; }?></td>
									<td class="text-l"><a href="{{url('house/houseLister/houseMap')}}"><u style="cursor:pointer" class="text-primary" title="查看"><?php echo mb_substr($val->house_location,0,20,'utf-8');?>..........</u></a></td>
									<td>{{$val->house_rise}}<b style="font-size:15px;">~</b>{{$val->house_duration}} /@lang('house_translate.Weeks2')</td>
									<td class="td-status">
										<span class="label label-success radius">
											@if($val->house_status == '预租')
												@lang('house_translate.Rent_in_advance')
											@elseif($val->house_status == '已锁定')
												@lang('house_translate.Has_been_locked')
											@elseif($val->house_status == '已出租')
												@lang('house_translate.Have_been_leased')
											@elseif($val->house_status == '配置中')
												@lang('house_translate.In_the_configuration')
											@elseif($val->house_status == '冻结')
												@lang('house_translate.freeze')
											@elseif($val->house_status == '暂停出租')
												@lang('house_translate.Suspension_of_rent')
											@endif
										</span>
									</td>
									<td>
										@if($val->chk_sta == 1)
											@lang('house_translate.Not_audit')
										@elseif($val->chk_sta == 2)
											@lang('house_translate.adopt')
										@elseif($val->chk_sta == 3)
											@lang('house_translate.Not_through')
										@endif
									</td>
									<td class="f-14 td-manage">
										<a href="{{ url('house/houseLister/update',['id'=>$val->msgid]) }}">@lang('house_translate.Update_the_housing')</a>
										&nbsp;||&nbsp;
										<a href="{{ url('house/houseLister/detail',['id'=>$val->msgid]) }}">@lang('house_translate.The_detailed_information')</a>
									</td>
							    </tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</article>
			</div>
			<!-- 分页 -->
			@if (!empty($houseObj))
				<div class="page_list">
					{{$houseObj->appends(Request::input())->links()}}
				</div>
			@endif

		</div>
	</div>


	@stop

	@section('js')
		<script>
			document.getElementById('findType').value='{{$type}}';
			document.getElementById('search_k').value='{{$search_k}}';
			document.getElementById('search_v').value='@if($search_v != "%"){{$search_v}}@endif';

			//常规用法 日期
			laydate.render({
				elem: '#rise'
			});
			laydate.render({
				elem: '#duration'
			});
		</script>
	@stop


