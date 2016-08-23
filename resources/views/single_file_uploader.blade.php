<?php
$id = isset($id)?$id:rand();
$target_uri = isset($target_uri)?$target_uri:'';
$select_button_label = isset($button_label)?$button_label:'Select and upload';
$send_button_label = isset($button_label)?$button_label:'Upload';
$cancel_button_label = isset($button_label)?$button_label:'Cancel';
$max_file_size = isset($max_file_size)?$max_file_size:'7000000';
$accept = isset($accept2)?$accept2:(isset($accept)?$accept:'image/jpeg,application/pdf');
?>

<div class="sbup" id="{{ $id }}">
<form class="sbup-form" action="{{ $target_uri }}" method="post" enctype="multipart/form-data" style="widht: 100%; max-width: 400px">
  <div>
    <div class="btn btn-warning sbup-select-btn" style="position: relative; overflow: hidden;">{{ $select_button_label }}
      <input type="file" accept="{{ $accept }}" class="sbup-field" name="input_file" style="position: absolute; top: -500px; left: -400px; height: 700px; width: 800px; opacity: 0;">
    </div>
    <input class="btn btn-danger sbup-upload-btn hidden" type="button" value="{{ $send_button_label }}">
    <input class="btn btn-danger sbup-cancel-btn hidden" type="button" value="{{ $cancel_button_label }}">
    <input type="hidden" class="sbup-max-file-size-field" name="MAX_FILE_SIZE" value="{{ $max_file_size }}" />
    <input type="hidden" class="sbup-xsrf-token" name="_token" value="" />
    <div style="margin: 8px 0;" class="progress progress-striped">
      <div class="progress-bar sbup-progress" style="width: 0%"></div>
    </div>
    <label class="sbup-status-message"></label><br/>
  </div>
</form>
</div>
