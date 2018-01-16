<?php

require_once( KANDA_INCLUDES_PATH . 'cron/class-booking-cancellation-notification.php' );
Kanda_Booking_Cancellation_Notification::get_instance()->process_range( 1, 3 );