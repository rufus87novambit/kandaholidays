<?php
$full_period = array();
$chunk_size = 7;
$index = 0;

$start_date = new DateTime( $period['start_date'] );
$end_date = new DateTime( $period['end_date'] );
$interval = DateInterval::createFromDateString('1 day');
$life_period = new DatePeriod( $start_date, $interval, $end_date );

foreach ( $life_period as $lp ) {
    $full_period[ $index ] = array_merge(
        IOL_Helper::room_status_data( $availability[ $index ] ),
        array(
            'date' => $lp->format( "d / m" ),
        )
    );
    ++ $index;
}
$period_chunks = array_chunk( $full_period, $chunk_size ); ?>

<h2 class="text-center"><?php esc_html_e( 'Room Availability', 'kanda' ); ?></h2>
<p class="text-center">
<?php
    printf(
        '%1$s - %2$s',
            date( Kanda_Config::get( 'display_date_format' ), $start_date->getTimestamp() ),
            date( Kanda_Config::get( 'display_date_format' ), $end_date->getTimestamp() )
    ); ?>
</p>

<div class="table-wrap">
    <?php foreach( $period_chunks as $chunk ) {  ?>
    <div class="users-table table text-center">
        <header class="thead">
            <?php foreach ( $chunk as $data ) { ?>
            <div class="th"><?php echo $data['date']; ?></div>
            <?php } ?>
        </header>
        <div class="tbody">
            <div class="tr">
                <?php foreach ( $chunk as $data ) { ?>
                <div class="td"><?php echo $data['icon']; ?> <small><?php echo $data['message']; ?></small></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>