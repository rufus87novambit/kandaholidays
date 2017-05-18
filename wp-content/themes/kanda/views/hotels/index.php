<?php
    echo $this->partial( 'hotel-search-form', array( 'form_class' => 'sync_with_sidebar' ) );
    echo kanda_get_loading_popup();
    echo kanda_get_error_popup();
?>