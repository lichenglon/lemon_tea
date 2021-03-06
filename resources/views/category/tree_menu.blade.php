
@foreach($tree as $values)
	<dl class="cate-item">
		<dt class="cf">
			<form action="{{ url('category/menu/edit') }}" method="post">
				<div class="btn-toolbar opt-btn cf">
					<a href="javascript:layer.msg('@lang('account.Non_developers_have_to_operate')', {icon: 2})">@lang('account.delete')</a>
				</div>
				<div class="fold"><i></i></div>
				<div class="order">{{ $values['id'] }}</div>
				<div class="order"><input type="text" onblur="layer.msg('@lang('account.Non_developers_have_to_operate')', {icon: 2})" name="sort_number" class="text input-mini" value="{{ $values['sort_number'] }}"></div>
				<div class="order">

				</div>
				<div class="name">
					<span class="tab-sign"></span>
					<input type="hidden" name="id" value="{{ $values['id'] }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="text" name="name" onblur="layer.msg('@lang('account.Non_developers_have_to_operate')', {icon: 2})" class="text" value="{{ $values['name'] }}">
					<input type="text" name="url" onblur="layer.msg('@lang('account.Non_developers_have_to_operate')', {icon: 2})" class="text" value="{{ $values['url'] }}">
					{{--<a class="add-sub-cate" title="添加子分类" href="{{ url('category/menu/create',['pid'=>$values['id']]) }} ">
						<i class="icon-add"></i>
					</a>--}}
					<a class="add-sub-cate" title="添加子分类" href="javascript:layer.msg('@lang('account.Non_developers_have_to_operate')', {icon: 2})">
						<i class="icon-add"></i>
					</a>
					<span class="help-inline msg"></span>
				</div>
			</form>
		</dt>
		@if(!empty($values['children']))
			<dd style="display:none;">
				@include('category.tree_menu', ['tree' => $values['children']])
			</dd>
		@endif
	</dl>
@endforeach