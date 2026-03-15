<div class="group_item_theme">
	<div class="icon_change_postion"><i class="fa fa-sort"></i></div>
	<div class="left_item_theme left_genate"><input type="text" value="{{ $code_name ?? '' }}" placeholder="Please enter Name Field"  name="header_option[img][name][]" /></div>
	<div class="right_item_theme right_genate">
		<div class="input-group">
			<div class="input-group">
				<input type="text" class="form-control input_image {{ $id ?? 'img' }}_view" name="header_option[img][value][]" id="{{ $name ?? 'image' }}" value="{{ $image ?? '' }}" autocomplete="off">
				<div class="input-group-append">
					<button class="btn btn-outline-secondary ckfinder-popup" type="button" id="{{ $id ?? 'img' }}"  data-show="{{ $id ?? 'img' }}_view" data="{{ $name ?? 'image' }}">Upload</button>
				</div>
			</div>
		</div>
	</div>
	<div class="action">
		<input type="button" class="button button-secondary tbl_button_delete_clean" value="Delete" name="delete_tbl"></div>
</div>
