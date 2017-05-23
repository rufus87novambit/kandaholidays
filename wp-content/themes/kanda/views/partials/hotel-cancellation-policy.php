<h2 class="text-center"><?php esc_html_e( 'Cancellation Policy', 'kanda' ); ?></h2>
<p></p>
<?php if( empty( $cancellation_policies ) ) { ?>
<p class="text-center"><?php _e( 'Cancellation policy data is empty', 'kanda' ); ?></p>
<?php } else { ?>
<div class="table-wrap">
    <div class="users-table table">
        <header class="thead">
            <div class="th"><?php esc_html_e( 'From', 'kanda' ); ?></div>
            <div class="th"><?php esc_html_e( 'To', 'kanda' ); ?></div>
            <div class="th"><?php esc_html_e( 'Charge', 'kanda' ); ?></div>
        </header>
        <div class="tbody">
            <?php
                foreach( $cancellation_policies as $policy ) {

                    $spare = Kanda_Config::get( 'spare_days_count' ) * 86400;

                    $from_timestamp = max( strtotime( $policy['fromdate'] ), time() ) - $spare;
                    $to_timestamp = min( strtotime( $policy['todate'] ), strtotime( $request['end_date'] ) );
                    if( $to_timestamp != strtotime( $request['end_date'] ) ) {
                        $to_timestamp -= $spare;
                    }
                    if( $to_timestamp <= time() ) {
                        continue;
                    } ?>
            <div class="tr">
                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), $from_timestamp ); ?></div>
                <div class="td"><?php echo date( Kanda_Config::get( 'display_date_format' ), $to_timestamp ); ?></div>
                <div class="td"><?php echo ( strtolower( $policy['percentoramt'] ) == 'a' ) ? sprintf( '%1$d %2$s', $policy['nighttocharge'], _n( 'night', 'nights', $policy['nighttocharge'], 'kanda' ) ) : sprintf( '%1$d%%', intval( $policy['value'] ) ); ?></div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>