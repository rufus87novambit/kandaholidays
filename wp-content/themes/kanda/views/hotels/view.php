<div id="hotel-details-box" class="hotel-details"
     data-hotel-code="<?php echo $this->hotel_code; ?>"
     data-security="<?php echo $this->security; ?>"
     data-start-date="<?php echo $this->start_date; ?>"
     data-end-date="<?php echo $this->end_date; ?>"
></div>
<?php
    echo kanda_get_loading_popup();
    echo kanda_get_error_popup();
?>