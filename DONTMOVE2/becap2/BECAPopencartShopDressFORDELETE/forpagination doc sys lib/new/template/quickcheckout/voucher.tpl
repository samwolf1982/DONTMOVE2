<?php if ($coupon_module) { ?>
<div id="coupon-heading"><?php echo $entry_coupon; ?></div>
<div id="coupon-content">
  <div class="input-group">
	<input type="text" name="coupon" value="" class="" />
	  <button type="button" id="button-coupon" class=""><?php echo $text_use_coupon; ?></button>
  </div>
</div>
<?php } ?>
<?php if ($voucher_module) { ?>
<div id="voucher-heading"><?php echo $entry_voucher; ?></div>
<div id="voucher-content">
  <div class="input-group">
	<input type="text" name="voucher" value="" class="" />
	  <button type="button" id="button-voucher" class=""><?php echo $text_use_voucher; ?></button>
  </div>
</div>
<?php } ?>
<?php if ($reward_module && $reward) { ?>
<div id="reward-heading"><?php echo $entry_reward; ?></div>
<div id="reward-content">
  <div class="input-group">
	<input type="text" name="reward" value="" class="" />
	  <button type="button" id="button-reward" class=""><?php echo $text_use_reward; ?></button>
  </div>
</div>
<?php } ?>

<script type="text/javascript"><!--
$('#coupon-heading').on('click', function() {
    if($('#coupon-content').is(':visible')){
      $('#coupon-content').slideUp('slow');
    } else {
      $('#coupon-content').slideDown('slow');
    };
});

$('#voucher-heading').on('click', function() {
    if($('#voucher-content').is(':visible')){
      $('#voucher-content').slideUp('slow');
    } else {
      $('#voucher-content').slideDown('slow');
    };
});

$('#reward-heading').on('click', function() {
    if($('#reward-content').is(':visible')){
      $('#reward-content').slideUp('slow');
    } else {
      $('#reward-content').slideDown('slow');
    };
});
//--></script>