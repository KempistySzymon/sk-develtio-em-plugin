<?php 
/**
 * Template Name: Single Event
 * Template Post Type: event
 */
get_header();
$event_id = get_the_ID();
$event_date  = get_field('em_field_date');
$event_limit = get_field('em_participant_limit');
$event_desc  = get_field('em_description');

$cities = get_the_terms( $event_id, 'city' );
$event_city_name = '';

if ( $cities && ! is_wp_error( $cities ) ) {
    $event_city_name = esc_html( $cities[0]->name ); // W tym założeniu wydarzenie ma jedną miejscowość
}

?>

<section id="event<?= $event_id; ?>" class="single_event">
    <div class="card">
        <div class="card_info">
            <h2 class="single_event_title event_title"><?= esc_html(get_the_title());?></h2>
            <span class="single_event_date event_date"><?= esc_html($event_date);?></span>
            <span class="single_event_city event_city"><?= esc_html($event_city_name); ?></span>
            <span class="single_event_limit event_limit">Limit miejsc: <?= esc_html($event_limit); ?></span>
            <div class="single_event_description event_description"><?php echo nl2br(esc_html($event_desc)); ?></div>
            <button onClick="registerPopup()">Zapisz się</button>
            
        </div>
        <div id="cardregister" class="card_register" style="display:none">
            <form id="em-registration-form">
                <input type="hidden" name="event_id" value="<?= get_the_ID(); ?>">
                <label for="em-name">Imię</label>
                <input type="text" name="name" id="em-name" placeholder="Jan Przykładowy" required>
                <label for="em-email">Email</label>
                <input type="email" name="email" id="em-email" placeholder="jan@przyklad.pl" required>
                <button type="submit">Zapisz się na wydarzenie</button>
                <div id="em-message"></div>
            </form>
        </div>
    </div>

</section>


<?php 
get_footer();
?>